<?php

namespace App\Http\Controllers;

use App\Models\Export;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function uploadDatabase(Request $request)
    {
        $username = 'root'; // Database username
        $database = 'upload_database'; // Your database name

        // Set the folder path where you want to store the backups
        $backupFolder = 'storage/backup/';

        // Check if the folder exists, if not, create it
        if (!is_dir($backupFolder)) {
            mkdir($backupFolder, 0777, true);
        }

        // Generate a unique file name by incrementing
        $increment = 1;
        do {
            $filePath = $backupFolder . "local_database_" . $increment . ".sql";
            $increment++;
        } while (file_exists($filePath));

        // Construct the mysqldump command without the password option
        $command = "D:\\xampp\\mysql\\bin\\mysqldump.exe -u $username $database > \"$filePath\"";

        try {
            // Execute the command
            $output = shell_exec($command);

            // Check if the file was created
            if (file_exists($filePath)) {
                // Return the response from sendSqlFile
                return $this->sendSqlFile($filePath, $output);
            } else {
                return response()->json(['message' => 'Failed to generate SQL file.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error uploading database: ' . $e->getMessage()], 500);
        }
    }

    public function sendSqlFile($path, $file)
    {
        try {
            $store = new Export();

            $store->sql_file = $path;
            $store->save();

            return response()->json(['message' => 'Database uploaded successfully.', 'file' => $path], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


}

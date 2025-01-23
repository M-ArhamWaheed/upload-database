<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DatabaseController extends Controller
{
    // public function uploadDatabase(Request $request)
    // {
    //     $username = 'root'; // Database username
    //     $database = 'upload_database'; // Your database name
    //     $filePath = 'local_database.sql'; // Path to save the SQL file

    //     // Construct the mysqldump command without the password option
    //     $command = "D:\\xampp\\mysql\\bin\\mysqldump.exe -u $username $database > $filePath";

    //     try {
    //         // Execute the command
    //         $output = shell_exec($command);

    //         // Check if the file was created
    //         if (file_exists($filePath)) {
    //             return response()->json(['message' => 'Database uploaded successfully.', 'file' => $filePath]);
    //         } else {
    //             return response()->json(['message' => 'Failed to generate SQL file.'], 500);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Error uploading database: ' . $e->getMessage()], 500);
    //     }
    // }
    public function uploadDatabase(Request $request)
    {
        $username = 'root'; // Database username
        $database = 'upload_database'; // Your database name

        // Set the folder path where you want to store the backups
        $backupFolder = 'backup/';

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
                return response()->json(['message' => 'Database uploaded successfully.', 'file' => $filePath]);
            } else {
                return response()->json(['message' => 'Failed to generate SQL file.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error uploading database: ' . $e->getMessage()], 500);
        }
    }


}

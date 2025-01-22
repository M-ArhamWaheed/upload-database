<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DatabaseController extends Controller
{

    public function uploadDatabase(Request $request)
    {
        try {
            $this->exportAndUploadDatabase();
            return response()->json(['message' => 'Database uploaded successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error uploading database: ' . $e->getMessage()], 500);
        }
    }

    private function exportAndUploadDatabase()
    {
        // Export local database
        $exportCommand = "D:\xampp\mysql\bin\mysqldump.exe -u root -p upload_database > local_database.sql";
        exec($exportCommand . " 2>&1", $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('Error exporting database. Output: ' . implode("\n", $output));
        }

        // FTP upload
        $ftpHost = 'healthylifemanager.com';
        $ftpUsername = 'arham@sms.healthylifemanager.com';
        $ftpPassword = '197350@Web271';

        $ftpConnection = ftp_connect($ftpHost);

        if (!$ftpConnection) {
            throw new \Exception('Unable to connect to FTP server.');
        }

        if (!ftp_login($ftpConnection, $ftpUsername, $ftpPassword)) {
            ftp_close($ftpConnection);
            throw new \Exception('FTP login failed.');
        }

        if (!ftp_put($ftpConnection, 'remote_database_backup.sql', 'local_database.sql', FTP_ASCII)) {
            ftp_close($ftpConnection);
            $error = error_get_last();
            throw new \Exception('Failed to upload the database file. Error: ' . $error['message']);
        }

        ftp_close($ftpConnection);

        // Clean up local file
        unlink('local_database.sql');
    }
}

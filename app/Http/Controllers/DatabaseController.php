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
        $exportCommand = "mysqldump -u root -p'' upload_database > local_database.sql";
        exec($exportCommand, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception('Error exporting database: ' . implode("\n", $output));
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

        if (!ftp_put($ftpConnection, '/path/to/remote_directory/remote_database_backup.sql', 'local_database.sql', FTP_ASCII)) {
            ftp_close($ftpConnection);
            throw new \Exception('Failed to upload the database file.');
        }

        ftp_close($ftpConnection);

        // Clean up local file
        unlink('local_database.sql');
    }
}

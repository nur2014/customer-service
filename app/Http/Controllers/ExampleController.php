<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class ExampleController extends Controller
{
    private $dbConInfo = [];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->dbConInfo = config('database.connections.mysql');
    }

    public function dumpDB()
    {
        try {
            // phpinfo();
            // die();
            // $this->createZip();
            // return;
            $host = $this->dbConInfo['host'];
            $user = $this->dbConInfo['username'];
            $pass = $this->dbConInfo['password'];
            $dbName = $this->dbConInfo['database'];
            $exportPathSql = 'common-service-db-' . (new \DateTime())->format('d-m-Y') . '.sql';
            $exportPathZip = 'common-service-db-' . (new \DateTime())->format('d-m-Y') . '.zip';
            $exportPathSql = storage_path('db-backup'. DIRECTORY_SEPARATOR  . $exportPathSql);
            $exportPathZip = storage_path('db-backup'. DIRECTORY_SEPARATOR  . $exportPathZip);

            //Please do not change the following points
            //Export of the database and output of the status
            $command='mysqldump --opt -h' . $host .' -u' . $user . ' -p' . $pass . ' ' . $dbName .' > ' . 'db.sql';
            exec($command);
            $output = [];
            $resultCode = null;
            echo $command;
            Log::info("DB backup started:");


            Log::info("Backup output:");
            Log::info(implode(',', $output));
            Log::info('Backup result:' . $resultCode . "\nBackup end");

            if (file_exists($exportPathZip)) {
                unlink($exportPathZip);
            }

            $zip = new \ZipArchive();
            if ($zip->open($exportPathZip, \ZipArchive::CREATE) === TRUE) {
                $zip->addFile(storage_path($exportPathSql), $exportPathSql);
                $zip->close();
            } else {
                return response([
                    'success' => false,
                    'message' => "Failed to create zip"
                ]);
            }
            
        } catch (\Exception $ex) {
            return response([
                'success' => false,
                'message' => config('app.env') == 'production' ? "" : $ex->getMessage()
            ]);
            return $ex->getMessage();
        }

        return response([
            'success' => true,
            'message' => 'Db backup successful',
            'data' => $exportPathZip
        ]);
    }
      /** This download route will download file which has path like 
   * uploads/file_name or something like that
   * where full file in the project is like storage/uploads/file_name
   **/
  public function downloadBackupDb(Request $request)
  {
    return response()->download(base_path('storage/' . $request->file_name));
  }

  private function createZip()
  {
    $zip = new \ZipArchive;
    // echo $zip->open('test_new.zip', \ZipArchive::CREATE);
    $path = storage_path();
    // echo $path;
    if ($zip->open($path . '/test_new.zip', \ZipArchive::CREATE) === TRUE)
    {
        // Add files to the zip file
        $zip->addFile($path.'/my-backup.sql', 'my-backups.sql');
     
        // Add random.txt file to zip and rename it to newfile.txt
        $zip->addFile($path .'/random.txt', 'newfile.txt');
     
        // Add a file new.txt file to zip using the text specified
        $zip->addFromString('new.txt', 'text to be added to the new.txt file');
     
        // All files are added, so close the zip file.
        $zip->close();
    }
  }

    public function getDbBackupFiles()
    {
        $dir = new \DirectoryIterator(storage_path('db-backup'));

        foreach ($dir as $fileInfo) {
            if (!$fileInfo->isDot()) {
                var_dump($fileInfo->getFilename());
            }    
        }
    }
}
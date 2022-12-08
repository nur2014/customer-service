<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\DbBackup;
use Log;

class DataArchiveController extends Controller
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

    public function getDbBackupFiles()
    {
        $dirPath = storage_path('db-backup');

        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0755);
        }

        //Converting sql file into zile file
        $dir = new \DirectoryIterator($dirPath);
        foreach ($dir as $fileInfo) {
            if ($fileInfo->isDot()) {
               continue; 
            }
            $fileName = $fileInfo->getFilename();
            $filePath = $dirPath . DIRECTORY_SEPARATOR .$fileName;

            // $this->createZipfile($filePath, $fileName);
            $dbBackup = new DbBackup();
            $dbBackup->createZip($filePath, $fileName);
        }

        $dir = new \DirectoryIterator($dirPath);
        $fileList = [];
        foreach ($dir as $fileInfo) {
            if ($fileInfo->isDot()) {
               continue; 
            }
            
            $fileName = $fileInfo->getFilename();
            $filePath = $dirPath . DIRECTORY_SEPARATOR .$fileName;

            $fileSize = filesize($filePath);
            $fileSize = round($fileSize / 1000000, 3);
            $fileNameParts = explode('-', $fileName);
            $len = count($fileNameParts);

            $stdObj = new \stdClass();
            $stdObj->file_name = $fileName;
            $stdObj->file_size = $fileSize;
            $stdObj->created_date = substr($fileNameParts[$len - 1], 0, 4) . '-' . $fileNameParts[$len - 2] . '-' . $fileNameParts[$len - 3];
            $fileList[$fileName] = $stdObj;
            krsort($fileList);
        }
        
        return response([
            'success' => true,
            'data' => array_values($fileList)
        ]);
    }

    public function dumpDB()
    {
        $dbBackup = new DbBackup();
        $result = $dbBackup->backup();

        return response($result);
    }
    
    /** This download route will download file which has path like 
     * file_name
     **/
  public function downloadBackupDb(Request $request)
  {
    return response()->download(storage_path('db-backup/' . $request->file_name));
  }

  public function deleteDbBackupFile(Request $request)
  {
    $fullPath = storage_path("db-backup/{$request->file_name}");

    if (empty($request->file_name) || !file_exists($fullPath)) {
        return response([
            'success' => false,
            'message' => 'fileNotFound'
        ]);
    }

    unlink($fullPath);

    return response([
        'success' => true,
        'message' => 'File deleted successfully'
    ]);
  }  
}

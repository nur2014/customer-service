<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use App\Library\DbBackup;
use Log;

class DailyDbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will backup the database of every service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time = (new \DateTime())->format('Y-m-d H:i:s');
        \Log::info("DB Backup cron run @{$time}");
        $this->backupDb();
    }

    private function backupDb()
    {
        $dbBackup = new DbBackup();
        $backupResult = $dbBackup->backup();

        /** Backup db */
        if (!$backupResult['success'])
        {
            return $backupResult;
        }

        /** Create zip */
        $zipResult = $dbBackup->createZip($backupResult['file'], $backupResult['file_name']);

        if (!$zipResult['success']) {
            return $zipResult;
        }

        /** Sending email with backup db as attachment */
        $data['attachment'] = $zipResult['file'];
        $recipient = config('mail.db_backup_recipient');
        $this->emailDb($data, $recipient);

        return [
            'success' => true
        ];
    }

    /**
     * Sending backup db to email
     */
    private function emailDb($data, $recipient)
    {
        try {
            Mail::send('db-backup-email', $data, function ($message) use ($recipient, $data) {
                $message->to($recipient, '')->subject('Database Schedule backup');
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->attach($data['attachment']);
            });
          } catch (\Exception $ex) {
            Log::info("DB backup email failed. Error: {$ex->getMessage()}");
            return [
                'success' => false,
                'message' => "DB backup email failed. Error: {$ex->getMessage()}"
            ];
          }
    
          Log::info("DB backup email sent successfully. Recipient: {$recipient}");
          return [
              'success' => true
          ];
    }
}

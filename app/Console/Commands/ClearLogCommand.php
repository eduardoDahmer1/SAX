<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogCommand extends Command
{
    protected $signature = 'log:clear';
    protected $description = 'Clear old log files';

    public function handle()
    {
        $logPath = storage_path('logs');

        $files = File::glob($logPath . '/*.log');

        foreach ($files as $file) {
            $fileDate = File::lastModified($file);
            $fileAge = now()->diffInDays($fileDate);

            if ($fileAge >= 14) {
                File::delete($file);
                $this->info('Deleted log file: ' . $file);
            }
        }

        $this->info('Log files cleared successfully!');
    }
}

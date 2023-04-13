<?php

namespace App\Http\Controllers;

use Artisan;
use Exception;
use Log;

class BackupControllerDB extends Controller
{

    public function createDB()
    {
        $command = config('backpack.backupmanager.artisan_command_on_button_click_db_only') ?? 'backup:run';

        try {
            foreach (config('backpack.backupmanager.ini_settings', []) as $setting => $value) {
                ini_set($setting, $value);
            }
            config(['backup.backup.destination.filename_prefix' => config('backpack.backupmanager.db_prefix', 'DB').'_']);

            Log::info('Backpack\BackupManager -- Called backup:run from admin interface');

            Artisan::call($command);

            $output = Artisan::output();
            if (strpos($output, 'Backup failed because')) {
                preg_match('/Backup failed because(.*?)$/ms', $output, $match);
                $message = "Backpack\BackupManager -- backup process failed because ".($match[1] ?? '');
                Log::error($message.PHP_EOL.$output);

                return response($message, 500);
            }
        } catch (Exception $e) {
            Log::error($e);

            return response($e->getMessage(), 500);
        }

        return true;
    }

}

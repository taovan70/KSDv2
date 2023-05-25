<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;


class ClearTempImagesFolderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-temp-images-folder-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all images in temp folder';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $file = new Filesystem;
        $file->cleanDirectory('storage/app/public/temp_images');
    }
}

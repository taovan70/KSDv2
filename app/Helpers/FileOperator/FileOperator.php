<?php

namespace App\Helpers\FileOperator;

use Illuminate\Support\Facades\Storage;

abstract class FileOperator
{
    public abstract static function save(string $src, string $path, ?int $index = null): ?string;

    /**
     * @param string $path
     * @return void
     */
    public static function deleteFolder(string $path): void
    {
        Storage::disk('public')->deleteDirectory($path);
    }
}
<?php

namespace App\Helpers\FileOperator;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOperator
{
    const SRC_BASE64= 'base64';

    /**
     * @param string $src
     * @param string $path
     * @param int|null $index
     * @return string|null
     */
    public static function save(string $src, string $path, ?int $index = null): ?string
    {
        $filename = ($index ?? Str::random(40)) . '.png';

        switch (true) {
            case filter_var($src, FILTER_VALIDATE_URL):
                $imageData = file_get_contents($src);
                break;
            default:
                $imageData = substr($src, strpos($src, ',') + 1);
                $imageData = base64_decode($imageData);
                break;
        }

        Storage::disk('public')->put($path . $filename, $imageData);

        return Storage::url($path . $filename);
    }
}
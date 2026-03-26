<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageLibraryHelper
{
    public static function getTutorialImages()
    {
        $files = Storage::disk('public')->files('tutorials');
        return array_filter($files, function($file) {
            return preg_match('/\.(jpg|jpeg|png|gif|svg)$/i', $file);
        });
    }
}

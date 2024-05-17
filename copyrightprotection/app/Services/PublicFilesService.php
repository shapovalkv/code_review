<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Mime\MimeTypes;

class PublicFilesService
{
    const PUBLIC_RESOURCE_SOURCE = '/resources-images/';

    public function store($file, $source)
    {
        if ($file) {
            $file_name = time().rand(11, 99).$file->getClientOriginalName();

            Storage::disk('s3')->put(self::PUBLIC_RESOURCE_SOURCE . $file_name, file_get_contents($file->getRealPath()));
            Storage::disk('s3')->setVisibility(self::PUBLIC_RESOURCE_SOURCE . $file_name, 'public');
            unlink($file->getRealPath());

            return Storage::disk('s3')->url($source . $file_name);
        }
    }

    public function delete($url): ?bool
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (Storage::disk('s3')->exists($path)) {
            return Storage::disk('s3')->delete($path);
        } return null;
    }
}

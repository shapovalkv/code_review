<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Symfony\Component\Mime\MimeTypes;

class FilesService
{
    public function store($file, $projectId, $upload_user_id)
    {
        if ($file) {
            $file_name = time().rand(11, 99).$file->getClientOriginalName();

            Storage::disk('s3')->put('/projects/' . $projectId . '/' . $file_name, file_get_contents($file->getRealPath()), 'private');
            unlink($file->getRealPath());

            return File::create([
                'user_project_id' => $projectId,
                'name' => $file_name,
                'upload_user_id' => $upload_user_id
            ]);
        }
    }

    public function delete(File $file)
    {
        $filePathOnS3 = 'projects/' . $file->user_project_id . '/' . $file->name;
        if (Storage::disk('s3')->exists($filePathOnS3)) {
            Storage::disk('s3')->delete($filePathOnS3);
            $file->delete();
        }
    }

    public function download(File $file)
    {

        $filePathOnS3 = 'projects/' . $file->user_project_id . '/' . $file->name;
        // Check if the file exists on S3
        if (Storage::disk('s3')->exists($filePathOnS3)) {
            // Get the file contents from S3

            $fileContents = Storage::disk('s3')->temporaryUrl(
                $file->name,
                now()->addMinutes(5),
                [
                    'ResponseContentType' => 'application/octet-stream',
                    'ResponseContentDisposition' => 'attachment; filename="' . $file->name . '"',
                ]
            );

            return response()->stream(
                function () use ($fileContents) {
                    echo $fileContents;
                },
                200,
                [
                    'Content-Type' => 'application/octet-stream',
                    'Content-Disposition' => 'attachment; filename="' . $file->name . '"',
                ]
            );
        } else {
            // Handle the case when the file doesn't exist on S3
            return response('File not found on S3', 404);
        }
    }
}

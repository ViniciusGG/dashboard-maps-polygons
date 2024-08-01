<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;
use Illuminate\Support\Facades\Http;

class ZipAttachmentsService
{
    public function zipAttachments($messages, $userId)
    {
        $userFolderPath = $userId . '_' . Carbon::now()->format('Y-m-d_H-i-s');
        $zipFilePath = storage_path('app/public/attachments/' . $userFolderPath . '.zip');
        $userFolder = storage_path('app/public/attachments/' . $userFolderPath);

        $zip = new ZipArchive();

        $attachmentsExist = false;

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($messages['messages'] as $message) {
                foreach ($message->attachments as $attachment) {
                    $attachmentsExist = true;
                    $attachmentPath = $attachment->file_name;
                    $fileNameOriginal = basename(parse_url($attachmentPath, PHP_URL_PATH));
                    $tempImagePath = storage_path('app/public/attachments/' . $fileNameOriginal);

                    $response = Http::get($attachmentPath);
                    if ($response->successful()) {
                        if (!file_exists($userFolder)) {
                            mkdir($userFolder, 0755, true);
                        }

                        file_put_contents($tempImagePath, $response->body());

                        if (file_exists($tempImagePath)) {
                            copy($tempImagePath, $userFolder . '/' . $fileNameOriginal);
                        } else {
                            Log::error('Failed to save attachment temporarily: ' . $attachmentPath);
                        }

                        if (file_exists($tempImagePath)) {
                            unlink($tempImagePath);
                        }
                    } else {
                        Log::error('Failed to download attachment from AWS: ' . $attachmentPath);
                    }
                }
            }

            if ($attachmentsExist) {
                $this->addFilesToZip($zip, $userFolder);
                $zip->close();
            } else {
                Log::info('No attachments found for zipping.');
                return null;
            }

            Storage::deleteDirectory('public/attachments/' . $userFolderPath);

            return $zipFilePath;
        }

        return null;
    }

    private function addFilesToZip($zip, $folderPath)
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folderPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($folderPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
}

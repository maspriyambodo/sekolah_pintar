<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    private string $disk;

    public function __construct(string $disk = 's3')
    {
        $this->disk = $disk;
    }

    public function upload(
        UploadedFile $file,
        ?int $userId = null,
        ?string $folder = null,
        ?string $fileName = null
    ): array {
        $path = $this->generatePath($userId, $folder);
        $fileName = $fileName ?? $this->generateFileName($file);
        $fullPath = $path . '/' . $fileName;

        $stored = Storage::disk($this->disk)->putFileAs(
            $path,
            $file,
            $fileName,
            'private'
        );

        if (!$stored) {
            throw new \Exception('Failed to upload file');
        }

        return [
            'file_name' => $fileName,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $fullPath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'url' => $this->getUrl($fullPath),
        ];
    }

    public function getPresignedUrl(string $filePath, int $expirationMinutes = 15): string
    {
        return Storage::disk($this->disk)->temporaryUrl(
            $filePath,
            now()->addMinutes($expirationMinutes)
        );
    }

    public function delete(string $filePath): bool
    {
        return Storage::disk($this->disk)->delete($filePath);
    }

    public function exists(string $filePath): bool
    {
        return Storage::disk($this->disk)->exists($filePath);
    }

    private function generatePath(?int $userId = null, ?string $folder = null): string
    {
        $segments = [];

        // Year/Month structure
        $segments[] = now()->format('Y');
        $segments[] = now()->format('m');

        // User folder
        if ($userId) {
            $segments[] = 'user-' . $userId;
        }

        // Custom folder
        if ($folder) {
            $segments[] = $folder;
        }

        return implode('/', $segments);
    }

    private function generateFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::uuid()->toString() . ($extension ? '.' . $extension : '');
    }

    private function getUrl(string $filePath): string
    {
        return Storage::disk($this->disk)->url($filePath);
    }

    public function validateFile(UploadedFile $file, array $allowedMimeTypes = [], int $maxSize = 10485760): void
    {
        if (!$file->isValid()) {
            throw new \Exception('Invalid file upload');
        }

        if ($file->getSize() > $maxSize) {
            throw new \Exception('File size exceeds maximum allowed size');
        }

        if (!empty($allowedMimeTypes) && !in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new \Exception('Invalid file type');
        }
    }
}

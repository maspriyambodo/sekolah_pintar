<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\FileUploadService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    use ApiResponseTrait;

    private FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function upload(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'file' => 'required|file|max:10240', // Max 10MB
                'folder' => 'nullable|string|max:100',
            ]);

            $file = $request->file('file');
            $folder = $request->input('folder');
            $userId = $request->user()?->id;

            // Validate file
            $this->fileUploadService->validateFile($file, [
                'image/jpeg',
                'image/png',
                'image/gif',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]);

            $result = $this->fileUploadService->upload($file, $userId, $folder);

            return $this->createdResponse($result, 'File uploaded successfully');
        } catch (\Exception $e) {
            Log::error('File upload failed', ['error' => $e->getMessage()]);
            return $this->errorResponse('File upload failed: ' . $e->getMessage(), 400);
        }
    }

    public function getPresignedUrl(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'file_path' => 'required|string',
                'expiration' => 'nullable|integer|min:1|max:60',
            ]);

            $filePath = $request->input('file_path');
            $expiration = (int) $request->input('expiration', 15);

            if (!$this->fileUploadService->exists($filePath)) {
                return $this->notFoundResponse('File not found');
            }

            $url = $this->fileUploadService->getPresignedUrl($filePath, $expiration);

            return $this->successResponse([
                'url' => $url,
                'expires_in' => $expiration * 60,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to generate presigned URL', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to generate presigned URL', 500);
        }
    }

    public function delete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'file_path' => 'required|string',
            ]);

            $filePath = $request->input('file_path');

            if (!$this->fileUploadService->exists($filePath)) {
                return $this->notFoundResponse('File not found');
            }

            $deleted = $this->fileUploadService->delete($filePath);

            if ($deleted) {
                return $this->successResponse(null, 'File deleted successfully');
            }

            return $this->errorResponse('Failed to delete file', 500);
        } catch (\Exception $e) {
            Log::error('Failed to delete file', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete file', 500);
        }
    }
}

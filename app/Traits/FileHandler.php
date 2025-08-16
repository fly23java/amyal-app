<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait FileHandler
{
    /**
     * Upload a file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $path
     * @param  string|null  $oldFile
     * @param  array  $options
     * @return string|false
     */
    protected function uploadFile(UploadedFile $file, $path = 'uploads', $oldFile = null, $options = [])
    {
        try {
            // Validate file
            if (!$this->validateFile($file, $options)) {
                return false;
            }

            // Generate unique filename
            $filename = $this->generateFilename($file, $options);

            // Store file
            $filePath = $file->storeAs($path, $filename, $options['disk'] ?? 'public');

            if ($filePath) {
                // Delete old file if exists
                if ($oldFile && Storage::disk($options['disk'] ?? 'public')->exists($oldFile)) {
                    Storage::disk($options['disk'] ?? 'public')->delete($oldFile);
                }

                // Process image if needed
                if ($this->isImage($file) && isset($options['resize'])) {
                    $this->resizeImage($filePath, $options['resize'], $options['disk'] ?? 'public');
                }

                Log::info('File uploaded successfully', [
                    'original_name' => $file->getClientOriginalName(),
                    'stored_path' => $filePath,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ]);

                return $filePath;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'path' => $path
            ]);

            return false;
        }
    }

    /**
     * Upload multiple files.
     *
     * @param  array  $files
     * @param  string  $path
     * @param  array  $options
     * @return array
     */
    protected function uploadMultipleFiles($files, $path = 'uploads', $options = [])
    {
        $uploadedFiles = [];
        $failedFiles = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $result = $this->uploadFile($file, $path, null, $options);
                
                if ($result) {
                    $uploadedFiles[] = $result;
                } else {
                    $failedFiles[] = $file->getClientOriginalName();
                }
            }
        }

        return [
            'success' => $uploadedFiles,
            'failed' => $failedFiles,
            'total' => count($files),
            'successful' => count($uploadedFiles),
            'failed_count' => count($failedFiles)
        ];
    }

    /**
     * Delete a file.
     *
     * @param  string  $filePath
     * @param  string  $disk
     * @return bool
     */
    protected function deleteFile($filePath, $disk = 'public')
    {
        try {
            if (Storage::disk($disk)->exists($filePath)) {
                $deleted = Storage::disk($disk)->delete($filePath);
                
                if ($deleted) {
                    Log::info('File deleted successfully', [
                        'file_path' => $filePath,
                        'disk' => $disk
                    ]);
                }
                
                return $deleted;
            }

            return true; // File doesn't exist, consider it deleted

        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
                'disk' => $disk
            ]);

            return false;
        }
    }

    /**
     * Move a file.
     *
     * @param  string  $oldPath
     * @param  string  $newPath
     * @param  string  $disk
     * @return bool
     */
    protected function moveFile($oldPath, $newPath, $disk = 'public')
    {
        try {
            if (Storage::disk($disk)->exists($oldPath)) {
                $moved = Storage::disk($disk)->move($oldPath, $newPath);
                
                if ($moved) {
                    Log::info('File moved successfully', [
                        'old_path' => $oldPath,
                        'new_path' => $newPath,
                        'disk' => $disk
                    ]);
                }
                
                return $moved;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('File move failed', [
                'error' => $e->getMessage(),
                'old_path' => $oldPath,
                'new_path' => $newPath,
                'disk' => $disk
            ]);

            return false;
        }
    }

    /**
     * Copy a file.
     *
     * @param  string  $sourcePath
     * @param  string  $destinationPath
     * @param  string  $disk
     * @return bool
     */
    protected function copyFile($sourcePath, $destinationPath, $disk = 'public')
    {
        try {
            if (Storage::disk($disk)->exists($sourcePath)) {
                $copied = Storage::disk($disk)->copy($sourcePath, $destinationPath);
                
                if ($copied) {
                    Log::info('File copied successfully', [
                        'source_path' => $sourcePath,
                        'destination_path' => $destinationPath,
                        'disk' => $disk
                    ]);
                }
                
                return $copied;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('File copy failed', [
                'error' => $e->getMessage(),
                'source_path' => $sourcePath,
                'destination_path' => $destinationPath,
                'disk' => $disk
            ]);

            return false;
        }
    }

    /**
     * Get file URL.
     *
     * @param  string  $filePath
     * @param  string  $disk
     * @return string|null
     */
    protected function getFileUrl($filePath, $disk = 'public')
    {
        try {
            if (Storage::disk($disk)->exists($filePath)) {
                return Storage::disk($disk)->url($filePath);
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Failed to get file URL', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
                'disk' => $disk
            ]);

            return null;
        }
    }

    /**
     * Get file size.
     *
     * @param  string  $filePath
     * @param  string  $disk
     * @return int|null
     */
    protected function getFileSize($filePath, $disk = 'public')
    {
        try {
            if (Storage::disk($disk)->exists($filePath)) {
                return Storage::disk($disk)->size($filePath);
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Failed to get file size', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
                'disk' => $disk
            ]);

            return null;
        }
    }

    /**
     * Check if file exists.
     *
     * @param  string  $filePath
     * @param  string  $disk
     * @return bool
     */
    protected function fileExists($filePath, $disk = 'public')
    {
        try {
            return Storage::disk($disk)->exists($filePath);
        } catch (\Exception $e) {
            Log::error('Failed to check file existence', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
                'disk' => $disk
            ]);

            return false;
        }
    }

    /**
     * Validate file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  array  $options
     * @return bool
     */
    protected function validateFile(UploadedFile $file, $options = [])
    {
        // Check file size
        $maxSize = $options['max_size'] ?? config('filesystems.max_file_size', 10240); // 10MB default
        if ($file->getSize() > $maxSize * 1024) {
            Log::warning('File size exceeds limit', [
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'max_size' => $maxSize * 1024
            ]);
            return false;
        }

        // Check file type
        $allowedTypes = $options['allowed_types'] ?? config('filesystems.allowed_types', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedTypes)) {
            Log::warning('File type not allowed', [
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $extension,
                'allowed_types' => $allowedTypes
            ]);
            return false;
        }

        return true;
    }

    /**
     * Generate unique filename.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  array  $options
     * @return string
     */
    protected function generateFilename(UploadedFile $file, $options = [])
    {
        $extension = $file->getClientOriginalExtension();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        
        // Clean original name
        $cleanName = preg_replace('/[^a-zA-Z0-9\-\_]/', '', $originalName);
        
        // Generate unique part
        $unique = Str::random(8);
        
        // Add timestamp if needed
        if (isset($options['add_timestamp']) && $options['add_timestamp']) {
            $timestamp = now()->format('Y-m-d_H-i-s');
            return "{$cleanName}_{$timestamp}_{$unique}.{$extension}";
        }
        
        return "{$cleanName}_{$unique}.{$extension}";
    }

    /**
     * Check if file is an image.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return bool
     */
    protected function isImage(UploadedFile $file)
    {
        $imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        return in_array($file->getMimeType(), $imageTypes);
    }

    /**
     * Resize image.
     *
     * @param  string  $filePath
     * @param  array  $dimensions
     * @param  string  $disk
     * @return bool
     */
    protected function resizeImage($filePath, $dimensions, $disk = 'public')
    {
        try {
            $fullPath = Storage::disk($disk)->path($filePath);
            
            $image = Image::make($fullPath);
            
            // Resize if dimensions provided
            if (isset($dimensions['width']) && isset($dimensions['height'])) {
                $image->resize($dimensions['width'], $dimensions['height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } elseif (isset($dimensions['width'])) {
                $image->resize($dimensions['width'], null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } elseif (isset($dimensions['height'])) {
                $image->resize(null, $dimensions['height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            
            // Save resized image
            $image->save($fullPath);
            
            return true;

        } catch (\Exception $e) {
            Log::error('Image resize failed', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
                'dimensions' => $dimensions
            ]);

            return false;
        }
    }

    /**
     * Create thumbnail.
     *
     * @param  string  $filePath
     * @param  int  $width
     * @param  int  $height
     * @param  string  $disk
     * @return string|false
     */
    protected function createThumbnail($filePath, $width = 150, $height = 150, $disk = 'public')
    {
        try {
            if (!$this->isImage(new UploadedFile(Storage::disk($disk)->path($filePath), 'temp'))) {
                return false;
            }

            $fullPath = Storage::disk($disk)->path($filePath);
            $thumbnailPath = str_replace('.' . pathinfo($filePath, PATHINFO_EXTENSION), '_thumb.' . pathinfo($filePath, PATHINFO_EXTENSION), $filePath);
            $thumbnailFullPath = Storage::disk($disk)->path($thumbnailPath);

            $image = Image::make($fullPath);
            $image->fit($width, $height);
            $image->save($thumbnailFullPath);

            return $thumbnailPath;

        } catch (\Exception $e) {
            Log::error('Thumbnail creation failed', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
                'width' => $width,
                'height' => $height
            ]);

            return false;
        }
    }

    /**
     * Get file information.
     *
     * @param  string  $filePath
     * @param  string  $disk
     * @return array|null
     */
    protected function getFileInfo($filePath, $disk = 'public')
    {
        try {
            if (!Storage::disk($disk)->exists($filePath)) {
                return null;
            }

            $fullPath = Storage::disk($disk)->path($filePath);
            $stat = stat($fullPath);

            return [
                'name' => basename($filePath),
                'path' => $filePath,
                'size' => $stat['size'],
                'size_formatted' => $this->formatFileSize($stat['size']),
                'mime_type' => mime_content_type($fullPath),
                'extension' => pathinfo($filePath, PATHINFO_EXTENSION),
                'created_at' => date('Y-m-d H:i:s', $stat['ctime']),
                'modified_at' => date('Y-m-d H:i:s', $stat['mtime']),
                'url' => $this->getFileUrl($filePath, $disk)
            ];

        } catch (\Exception $e) {
            Log::error('Failed to get file info', [
                'error' => $e->getMessage(),
                'file_path' => $filePath,
                'disk' => $disk
            ]);

            return null;
        }
    }

    /**
     * Format file size.
     *
     * @param  int  $bytes
     * @return string
     */
    protected function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
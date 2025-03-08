<?php

namespace Simp\Uploader\upload_system\uploader\traits;

trait CommonFunction {

    public function addAllowedExtension(string $mime_type = 'image/*'): self
    {
        $this->allowed_extenstions[] = $mime_type;
        return $this;
    }

    public function addAllowedMaxSize(int $allowed_max_size = 500000): self
    {
        $this->allowed_max_size = $allowed_max_size;
        return $this;
    }

    protected function getExtensionFromMime($mime_type)
    {
        $mime_map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
            'application/pdf' => 'pdf',
            'application/zip' => 'zip',
            'application/x-rar-compressed' => 'rar',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'text/plain' => 'txt',
            'text/html' => 'html',
            'text/css' => 'css',
            'application/json' => 'json',
            'application/javascript' => 'js',
            'video/mp4' => 'mp4',
            'video/x-msvideo' => 'avi',
            'video/x-matroska' => 'mkv',
            'audio/mpeg' => 'mp3',
            'audio/wav' => 'wav'
        ];

        return $mime_map[$mime_type] ?? null;
    }

    public function getParseFilename(): string
    {
        return $this->parse_filename;
    }

    public function getParseFilesize(): int
    {
        return $this->parse_filesize;
    }

    public function getFileType(): string
    {
        return $this->file_type;
    }

    public function getSystemFilename(): string
    {
        return $this->system_filename;
    }

    public function getAllowedExtensions(): array
    {
        return $this->allowed_extenstions;
    }

    public function getAllowedMaxSize(): int
    {
        return $this->allowed_max_size;
    }

    public function getTempObject(): array
    {
        return $this->temp_object;
    }

    public function getFileObject(): array
    {
        return $this->file_object;
    }

    public function isValidated(): bool
    {
        return $this->validated;
    }
}
<?php

declare(strict_types=1);

namespace Neat\Helpers\UploadedFile;

class UploadedFile
{
    public function __construct(
        private string $name,
        private string $fullPath,
        private string $mime,
        private string $uploadedPath,
        private int $errorNum,
        private int $fileSize
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function fullPath(): string
    {
        return $this->fullPath;
    }

    public function mime(): string
    {
        return $this->mime;
    }

    public function uploadedPath(): string
    {
        return $this->uploadedPath;
    }

    public function errorNum(): int
    {
        return $this->errorNum;
    }

    public function fileSize(): int
    {
        return $this->fileSize;
    }
}
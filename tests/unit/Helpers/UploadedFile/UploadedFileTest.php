<?php

declare(strict_types=1);

use Neat\Helpers\UploadedFile\UploadedFile;
use PHPUnit\Framework\TestCase;

final class UploadedFileTest extends TestCase
{
    public function testUploadedFile(): void
    {
        $file = new UploadedFile(
            'myimage.jpg',
            '/full/path/to/file',
            'image/jpeg',
            '/tmp/phpn3FmFr',
            0,
            15488
        );

        // assert
        $this->assertInstanceOf(UploadedFile::class, $file);
        $this->assertSame(0, $file->errorNum());
        $this->assertSame(15488, $file->fileSize());
        $this->assertSame('/full/path/to/file', $file->fullPath());
        $this->assertSame('image/jpeg', $file->mime());
        $this->assertSame('myimage.jpg', $file->name());
        $this->assertSame('/tmp/phpn3FmFr', $file->uploadedPath());
    }
}
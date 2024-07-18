<?php

declare(strict_types=1);

namespace Neat\Attributes\Http\RequestSource;

use Attribute;
use Neat\Helpers\UploadedFile\UploadedFile;
use Neat\Helpers\UploadedFile\UploadedFileCollection;
use Neat\Http\Request;

#[Attribute(Attribute::TARGET_PARAMETER)]
class FromUpload
{
    public function __construct(private string $name = 'file')
    {
    }

    /**
     * Gets upload files from http request.
     */
    public function loadObject(Request $httpRequest): UploadedFileCollection
    {
        $collection = new UploadedFileCollection();

        if (($files = $httpRequest->files($this->name)) === null) {
            return $collection;
        }

        foreach ($files as $file) {

            $collection->add(new UploadedFile(
                $file[$this->name]['name'],
                $file[$this->name]['full_path'],
                $file[$this->name]['type'],
                $file[$this->name]['tmp_name'],
                $file[$this->name]['error'],
                $file[$this->name]['size']
            ));
        }

        return $collection;
    }
}
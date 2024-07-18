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

        // array or single file
        if (!is_array($files['name'])) {

            $collection->add(new UploadedFile(
                $files['name'],
                $files['full_path'],
                $files['type'],
                $files['tmp_name'],
                $files['error'],
                $files['size']
            ));

            return $collection;
        }

        // handle array
        $len = count($files['name']);

        for ($i = 0; $i < $len; $i++) {

            $collection->add(new UploadedFile(
                $files['name'][$i],
                $files['full_path'][$i],
                $files['type'][$i],
                $files['tmp_name'][$i],
                $files['error'][$i],
                $files['size'][$i]
            ));
        }

        return $collection;
    }
}
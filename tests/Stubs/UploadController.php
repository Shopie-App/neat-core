<?php
namespace Neat\Tests\Stubs;

use Neat\Attributes\Http\HttpPost;
use Neat\Attributes\Http\RequestSource\FromUpload;
use Neat\Helpers\UploadedFile\UploadedFileCollection;
use Neat\Http\ActionResult\ActionResult;

class UploadController
{
    public function __construct()
    {
    }

    /** 
     * Handles file uploads.
     */
    #[HttpPost]
    public function storeUploadedFiles(#[FromUpload] UploadedFileCollection $files): ActionResult
    {
        return OK($files);
    }
}
<?php

use PHPUnit\Framework\TestCase;
require_once ("../../../relative-paths.php");
require_once (LIBRARY_PATH . "/form/FileUploader.php");

class FileUploaderTest extends TestCase
{
    protected function setUp()
    {
    }

    /**
     * @expectedException Exception
     */
    public function testFileUploaderMustThrowExceptionWhenFileArrayNotSet()
    {
        new FileUploader($_FILES);
    }

    /**
     * @throws Exception
     */
    public function testFoo()
    {
        # Arrange
            $_FILES = [
                "filename" => [
                    "name" => "some_pics_name.jpg",
                    "type" => "image/jpeg",
                    "tmp_name" => "/tmp/php1F02.tmp",
                    "error" => 0,
                    "size" => 9939
                ]
            ];

        $fileUploader = new FileUploader($_FILES);
        $fileUploader->setFileMaxSizeInBytes(300)
            ->setFileName("some_file_name")
            ->setFilePath("/dir/tmp/")
            ->setAcceptedFileExtensions(["jpeg, jpg, png"]);

    }
}



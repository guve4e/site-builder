<?php

use PHPUnit\Framework\TestCase;
require_once ("../../../relative-paths.php");
require_once (LIBRARY_PATH . "/form/FormHandler.php");
require_once (LIBRARY_PATH . "/form/FileUploader.php");
require_once (LIBRARY_PATH . "/utility/FileManager.php");

class FormHandlerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testPrintForm()
    {
        // Arrange
        $expectedString ="<form method='POST' action='".
            "form-handler.php?entity=TestDataResource&verb=update" .
            "&parameter=12334&navigate=1&path_success=product&path_fail=home&" .
            "path_success_params=product:1/key:value&path_fail_params=product:1/key:value".
            "' >";

        $fh = new FormHandler();
        $fh->navigateTo("form-handler")
            ->setEntity("TestDataResource")
            ->setVerb("update")
            ->setParameter(12334)
            ->setNavigateAfterUpdate(true)
            ->setPathSuccess("product")
            ->setPathFail("home")
            ->setPathSuccessParameters('product:1/key:value')
            ->setPathFailParameters('product:1/key:value')
            ->printForm();

        $this->expectOutputString($expectedString);
    }

    /**
     * @throws Exception
     */
    public function testPrintFormWithFileUploader()
    {
        // Arrange
        $expectedString ="<form method='POST' action='".
            "form-handler.php?entity=TestDataResource&verb=update" .
            "&parameter=12334&navigate=1&path_success=product&path_fail=home&" .
            "path_success_params=product:1/key:value&path_fail_params=product:1/key:value' " .
            "enctype='multipart/form-data'>\n".
            "<input type='hidden' name='file_name' value='some_file_name'>\n" .
            "<input type='hidden' name='file_path' value='some_file_path'>\n" .
            "<input type='hidden' name='file_max_size' value='32'>\n" .
            "<input type='hidden' name='accepted_file_extensions' value='jpg/jpeg'>\n" .
            "<input type='file' name='files[]' multiple>";

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

        # Act
        $file = new FileUploader(new FileManager());
        $file->setFilePath("some_file_path")
            ->setFileName("some_file_name")
            ->setFileMaxSizeInBytes(32)
            ->setAcceptedFileExtensions(["jpg", "jpeg"]);

        $file->setUploadedFiles($_FILES);


        $fh = new FormHandler($file);
        $fh->navigateTo("form-handler")
            ->setEntity("TestDataResource")
            ->setVerb("update")
            ->setParameter(12334)
            ->setNavigateAfterUpdate(true)
            ->setPathSuccess("product")
            ->setPathFail("home")
            ->setPathSuccessParameters('product:1/key:value')
            ->setPathFailParameters('product:1/key:value')
            ->printFileUploadForm();

        $this->expectOutputString($expectedString);
    }

    /**
     * @throws Exception
     */
    public function testPrintFormAction()
    {
        // Arrange
        $expectedString = 'form-handler.php?entity=TestDataResource&verb=update' .
            '&parameter=12334&navigate=1&path_success=product&path_fail=home&' .
            'path_success_params=product:1/key:value&path_fail_params=product:1/key:value';

        $fh = new FormHandler();
        $fh->navigateTo("form-handler")
            ->setEntity("TestDataResource")
            ->setVerb("update")
            ->setParameter(12334)
            ->setNavigateAfterUpdate(true)
            ->setPathSuccess("product")
            ->setPathFail("home")
            ->setPathSuccessParameters('product:1/key:value')
            ->setPathFailParameters('product:1/key:value')
            ->printFormAction();

        $this->expectOutputString($expectedString);
    }
}



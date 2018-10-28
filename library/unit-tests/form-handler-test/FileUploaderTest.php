<?php

use PHPUnit\Framework\TestCase;
require_once ("../../../relative-paths.php");
require_once (LIBRARY_PATH . "/form/FileUploader.php");
require_once (LIBRARY_PATH . "/utility/FileManager.php");

class FileUploaderTest extends TestCase
{
    /**
     * @expectedException Exception
     */
    public function testFileUploaderMustThrowExceptionWhenFileArrayNotSet()
    {
        $fileUploader = new FileUploader(new FileManager);
        $fileUploader->setUploadedFiles($_FILES);
    }

    /**
     * @throws Exception
     */
    public function testPrintFormInputs()
    {
        # Arrange
        $expectedOutputString = "<input type='hidden' name='file_name' value='some_file_name'>\n" .
                                "<input type='hidden' name='file_path' value='/dir/tmp/'>\n" .
                                "<input type='hidden' name='file_max_size' value='300'>\n" .
                                "<input type='hidden' name='accepted_file_extensions' value='jpeg/jpg/png'>\n" .
                                "<input type='file' name='files[]' multiple>";
        # Act
        $fileUploader = new FileUploader(new FileManager);
        $fileUploader->setFileMaxSizeInBytes(300)
            ->setFileName("some_file_name")
            ->setFilePath("/dir/tmp/")
            ->setAcceptedFileExtensions(["jpeg", "jpg", "png"])
            ->printFormInputs();

        $this->expectOutputString($expectedOutputString);
    }

    /**
     * @throws Exception
     */
    public function testPrintFormWithSetters()
    {
        # Arrange
        $expectedOutputString =
            "<input type='hidden' name='file_name' value='some_file_name'>\n" .
            "<input type='hidden' name='file_path' value='/dir/tmp/'>\n" .
            "<input type='hidden' name='file_max_size' value='300'>\n" .
            "<input type='hidden' name='accepted_file_extensions' value='jpeg/jpg/png'>\n" .
            "<input type='file' name='files[]' multiple>";
        # Act
        $fileUploader = new FileUploader(new FileManager());
        $fileUploader->setFileMaxSizeInBytes(300)
            ->setFileName("some_file_name")
            ->setFilePath("/dir/tmp/")
            ->setAcceptedFileExtensions(["jpeg", "jpg", "png"])
            ->printFormInputs();

        $this->expectOutputString($expectedOutputString);
    }

    /**
     * @throws Exception
     */
    public function testPrintFormWithSetter()
    {
        # Arrange
        $_POST['file_name'] = "some_file_name";
        $_POST["file_path"] = "/dir/tmp/";
        $_POST["file_max_size"] = "300";
        $_POST["accepted_file_extensions"] = "jpeg/jpg/png";
        $_POST["some_other_key"] = "some_other_value";

        $expectedOutputString =
            "<input type='hidden' name='file_name' value='some_file_name'>\n" .
            "<input type='hidden' name='file_path' value='/dir/tmp/'>\n" .
            "<input type='hidden' name='file_max_size' value='300'>\n" .
            "<input type='hidden' name='accepted_file_extensions' value='jpeg/jpg/png'>\n" .
            "<input type='file' name='files[]' multiple>";

        # Act
        $fileUploader = new FileUploader(new FileManager());
        $fileUploader->setInfo($_POST)
            ->printFormInputs();

        # Assert
        $this->expectOutputString($expectedOutputString);

        # Lets make sure that the file elements are removed
        $this->assertEquals(["some_other_key" => "some_other_value"],$_POST);
    }

    /**
     * @throws Exception
     */
    public function testFoo()
    {
        # Arrange
        $_FILES = [
            "files" => [
                "name" => [
                    0 => "i-9_signed_Completed_4844511.pdf"
                    ],
                "type" => [
                    0 => "application/pdf"
                    ],
                "tmp_name" => [
                    0 => "C:\xampp\tmp\php85B2.tmp"
                ],
                "error" => [
                    0 => 0
                ],
                "size" => [
                    0 => 184399
                    ]
                ]
            ];

        # Act
        $fileUploader = new FileUploader(new FileManager());
        $fileUploader->setFileMaxSizeInBytes(184399)
            ->setFileName("some_file_name")
            ->setFilePath("/dir/tmp/")
            ->setAcceptedFileExtensions(["jpeg", "jpg", "png"]);

        $fileUploader->setUploadedFiles($_FILES['files']);

        $fileUploader->uploadFiles();

        # Assert
    }
}



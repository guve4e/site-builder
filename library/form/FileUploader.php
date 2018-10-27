<?php

class FileUploader
{
    private $errorsMap = [
        1 => "Exceeds upload_max_filesize in php configuration",
        2 => "Exceeds MAX_FILE_SIZE in hidden form field",
        3 => "Partially Upload",
        4 => "No File Uploaded",
        6 => "No temporary upload folder",
        7 => "Failed to write to disk",
        8 => "A php extension prevented the upload"
    ];

    private $acceptedFileExtensions = [];
    private $fileMaxSize;
    private $serverMaxSize;

    private function sanitizeFileName(string $fileName): string {
        return str_replace(' ', '_', $fileName);
    }

    /**
     * FileUploader constructor.
     * @param array $files
     * @throws Exception
     */
    public function __construct(FileManager $file, array $files)
    {
        if (!isset($files) || (sizeof($files) == 0))
            throw new Exception("Bad file array!");
    }

    public function setFileMaxSizeInBytes(int $bytes): FileUploader {

        return $this;
    }

    public function setFileMaxSizeInMegaBytes(int $megaBytes): FileUploader {

        return $this;
    }

    public function setFilePath(string $path): FileUploader {

        return $this;
    }

    public function setFileName(string $name): FileUploader {

        return $this;
    }

    public function setAcceptedFileExtensions(array $acceptedFileExtensions): FileUploader {

        return $this;
    }
}
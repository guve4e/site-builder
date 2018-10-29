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
    private $filePath;
    private $fileName;
    private $files;
    private $fileManager;

    private function getAcceptedFileExtensionsAsString()
    {
        return implode("/",$this->acceptedFileExtensions);
    }

    /**
     * FileUploader constructor.
     * @param FileManager $file
     * @throws Exception
     */
    public function __construct(FileManager $file)
    {
        $this->fileManager = $file;
    }

    public function setFileMaxSizeInBytes(int $bytes): FileUploader {
        $this->fileMaxSize = $bytes;
        return $this;
    }

    /**
     * @param string $path
     * @return FileUploader
     * @throws Exception
     */
    public function setFilePath(string $path): FileUploader {
        if (!isset($path))
            throw new Exception("Bad Value in setFilePath setter!");

        $this->filePath = $path;
        return $this;
    }

    /**
     * @param string $name
     * @return FileUploader
     * @throws Exception
     */
    public function setFileName(string $name): FileUploader {
        if (!isset($name))
            throw new Exception("Bad Value in setFileName setter!");
        
        $this->fileName = $name;
        return $this;
    }

    public function setAcceptedFileExtensions(array $acceptedFileExtensions): FileUploader {
        $this->acceptedFileExtensions = $acceptedFileExtensions;
        return $this;
    }

    public function setInfo(array &$info): FileUploader
    {
        if (isset($info['file_name'])) {
            $this->fileName = $info['file_name'];
            unset($info['file_name']);
        }
        if (isset($info['file_path'])) {
            $this->filePath = $info['file_path'];
            unset($info['file_path']);
        }
        if (isset($info['file_max_size'])) {
            $this->fileMaxSize = $info['file_max_size'];
            unset($info['file_max_size']);
        }
        if (isset($info['accepted_file_extensions']))
        {
            $this->acceptedFileExtensions = explode("/", $info['accepted_file_extensions']);
            unset($info['accepted_file_extensions']);
        }

        return $this;
    }

    /**
     * @param array $files
     * @return FileUploader
     * @throws Exception
     */
    public function setUploadedFiles(array $files)
    {
        if (!isset($files) || (0 == sizeof($files)))
            throw new Exception("Bad file array!");

        $this->files = $files;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function uploadFiles()
    {
        if (is_array($this->files['name'])) {
            foreach ($this->files['name'] as $key => $value) {
                // Todo this is copying is not needed
                $currentFile['name'] = $this->files['name'][$key];
                $currentFile['type'] = $this->files['type'][$key];
                $currentFile['tmp_name'] = $this->files['tmp_name'][$key];
                $currentFile['error'] = $this->files['error'][$key];
                $currentFile['size'] = $this->files['size'][$key];
                if ($this->checkFile($currentFile)) {
                    $this->moveFile($currentFile);
                }
            }
        } else {
            if ($this->checkFile($this->files['name'])) {
                $this->moveFile($this->files['name']);
            }
        }
    }

    public function printFormInputs()
    {
        if (isset($this->fileName)) echo "<input type='hidden' name='file_name' value='{$this->fileName}'>\n";
        if (isset($this->filePath)) echo "<input type='hidden' name='file_path' value='{$this->filePath}'>\n";
        if (isset($this->fileMaxSize)) echo "<input type='hidden' name='file_max_size' value='{$this->fileMaxSize}'>\n";
        if (isset($this->acceptedFileExtensions))
            echo "<input type='hidden' name='accepted_file_extensions' value='{$this->getAcceptedFileExtensionsAsString()}'>\n";

        echo "<input type='file' name='files[]' multiple>";
    }

    private function checkFile(array $file)
    {
        if ($file['error'] != 0) {
            $this->getErrorMessage($file);
            return false;
        }
        if (!$this->isRightSize($file)) {
            return false;
        }
//        if ($this->typeCheckingOn) {
//            if (!$this->checkType($file)) {
//                return false;
//            }
//        }
//        $this->checkName($file);
        return true;
    }

    /**
     * Move files from php temp directory
     * to specific folder
     * @param $file
     * @return bool
     */
    private function moveFile($file)
    {
        $filename = isset($this->fileName) ? $this->fileName : $file['name'];

        // Move the files from tmp dir to the desired one
        $destination = $this->filePath . "/" . $filename . ".jpg";
        $tmpFile = $file['tmp_name'];

        $success = false;

        if ($this->fileManager->isUploadedFile($tmpFile))
            $success = $this->fileManager->movedUploadedFile($tmpFile,  $destination);

        return $success;
    }

    private function isRightSize(array $file)
    {
        $size = $file['size'];

        // if max file is equal or less
        return $this->fileMaxSize >= $size;
    }

    private function getErrorMessage(array $file)
    {
        $error = $file['error'];

        // loop trough errors map
        foreach($this->errorsMap as $key => $value)
        {
            if ($key == $error)
                return $value;
        }
        return false;
    }
}
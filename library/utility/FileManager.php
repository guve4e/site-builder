<?php
/**
 * Wrapper to some file manipulation functions.
 * Why Wrapper? For dependency injection, easy to unit test.
 */

class FileManager {

    /**
     * Determines the type of the JSON Error
     * and throws exception with right message.
     * @throws Exception
     */
    private function handleJsonErrors(string $jsonString)
    {
        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                $msg = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $msg = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $msg = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $msg = 'Syntax error, malformed JSON.';
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $msg = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $msg = 'One or more recursive references in the value to be encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $msg = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $msg = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $msg = 'Unknown JSON error occurred.';
                break;
        }

        return "json_decode failed with message: {$msg}\n" . "Original Json: {$jsonString}";
    }


    /**
     * Decodes the string provided as Json.
     * @param string data to be decoded.
     * @param bool $arr returns array or object, if true
     * @return array json
     *
     * @throws Exception
     */
    public function jsonDecode(string $data, bool $arr = true)
    {
        $res = json_decode($data, $arr);
        // check for successful decode
        if ($res === false || is_null($res))
            throw new Exception($this->handleJsonErrors($data));

        return $res;
    }

    /**
     * Encodes string data as JSON.
     * @param $data string or object to be encoded
     * @param int $optionFlags
     * @return string JSON string
     * @throws Exception
     */
    public function jsonEncode($data, $optionFlags = JSON_PRETTY_PRINT) : string
    {
        $res = json_encode($data, $optionFlags);
        // check for successful encode
        if ($res === false || is_null($res))
            $this->handleJsonErrors($data);

        return $res;
    }

    /**
     * Checks if the file specified exists.
     * @param $filePath string representing file path
     *
     * @return bool if file exists or not
     */
    public function fileExists(string $filePath) : bool
    {
        return file_exists($filePath);
    }

    /**
     * Wrapper over file_get_contents
     * @param string $fileName
     * @return string
     * @throws Exception
     */
    public function loadFileContent(string $fileName) : string
    {
        // open file and get contents
        $stringContent = file_get_contents($fileName);

        if ($stringContent === false)
            throw new Exception("file_put_contents failed");

        return $stringContent;
    }

    /**
     * Wrapper over file_put_contents
     * @param string $fileName
     * @param string $message
     * @param $flags
     * @return int
     * @throws Exception
     */
    public function writeFileContent(string $fileName, string $message, $flags) : int
    {
        // open file and write contents
        $res = file_put_contents($fileName, $message, $flags);

        if ($res === false)
            throw new Exception("file_put_contents failed");

        return $res;
    }

    /**
     * Opens socket and returns file descriptor.
     * @param $host
     * @param $port
     * @param $socketTimeout
     * @return resource
     * @throws Exception
     */
    public function socket(string $host, int $port, $socketTimeout)
    {
        $fp = fsockopen($host, $port, $errno, $errstr, $socketTimeout);

        if (!$fp)
            throw new Exception("$errstr {$errno}\n");

        return $fp;
    }

    /**
     * Writes bytes to file handle.
     * @param $fileDescriptor resource pointer to file
     * @param string $content content to be written
     * @return bool|int how many bytes are actually written or false
     * @throws Exception
     */
    public function write($fileDescriptor, string $content)
    {
        $bytesWritten = fwrite($fileDescriptor, $content);
        if ($bytesWritten === false)
            throw new Exception("fwrite couldn't execute!");

        return $bytesWritten;
    }

    /**
     * Wrapper over move_uploaded_file
     * @param string $source
     * @param $destination
     * @return bool
     */
    public function movedUploadedFile(string $source, string $destination)
    {
        return move_uploaded_file($source, $destination);
    }

    /**
     * Wrapper over move_uploaded_file
     * @param string $tempFile
     * @param $destination
     * @return bool
     */
    public function isUploadedFile(string $tempFilePath)
    {
        return is_uploaded_file($tempFilePath);
    }

    /**
     * Checks if the end of file.
     * @param $fileDescriptor
     * @return bool
     */
    public function endOfFile($fileDescriptor)
    {
        return feof($fileDescriptor);
    }

    /**
     * Reads line from a file.
     * @param $fileDescriptor
     * @param int $length
     * @return bool|string
     */
    public function getLine($fileDescriptor, int $length)
    {
        return fgets($fileDescriptor, $length);
    }

    /**
     * Closes file.
     * @param $fileDescriptor
     */
    public function close($fileDescriptor)
    {
        fclose($fileDescriptor);
    }
}

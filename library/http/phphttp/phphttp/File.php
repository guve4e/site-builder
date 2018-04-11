<?php
/**
 * Wrapper to some file manipulation functions.
 * Why Wrapper? For dependency injection, easy to unit test.
 */

class File {

    /**
     * Decodes the string provided as Json.
     * @param string data to be decoded.
     * @param bool $arr returns array or object, if true
     * it returns array.
     * @return array json
     *
     * @throws Exception
     */
    public function jsonDecode(string $data, bool $arr = true) : array
    {
        $res = json_decode($data, $arr);
        // check for successful decode
        if ($res === false) throw new Exception("json_decode failed");

        return $res;
    }

    /**
     * Encodes string data as JSON.
     * @param string $data data to be encoded
     * @return string JSON string
     * @throws Exception
     */
    public function jsonEncode(string $data, $optionFlags = JSON_PRETTY_PRINT) : string
    {
        $res = json_encode($data, $optionFlags);
        // check for successful encode
        if ($res === false) throw new Exception("json_encode failed");

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
     * @return string
     */
    public function loadFileContent(string $fileName) : string
    {
        // open file and get contents
        $stringContent = file_get_contents($fileName);

        return $stringContent;
    }

    /**
     * Wrapper over file_put_contents
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
     * Checks if the end of file.
     * @param $fileDescriptor
     * @return bool
     */
    public function endOfFile($fileDescriptor)
    {
        echo ("A ");
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

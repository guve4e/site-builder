<?php
/**
 * Wrapper to some file manipulation functions.
 *
 */

class File {

    /**
     * Encodes the string provided as Json.
     * @param string string representation of file content.
     * @return array json
     *
     * @throws Exception
     */
    public function jsonDecode(string $fileContent) : array
    {
        // decode the info in json
        $res = json_decode($fileContent, true);
        // check for successful decode
        if ($res === false) throw new Exception("json_decode failed");
        // return the json
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
     * @return string
     */
    public function loadFileContent(string $fileName) : string
    {
        // open file and get contents
        $stringContent = file_get_contents($fileName);

        return $stringContent;
    }
}

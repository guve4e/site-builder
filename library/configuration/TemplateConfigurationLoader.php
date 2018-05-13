<?php
require_once (UTILITY_PATH . "/JsonLoader.php");

/**
 * Loads site configuration json file.
 * This file is located at:
 * "/template/config.json".
 */
class TemplateConfigurationLoader
{
    /**
     * @var string
     */
    private $nameOfJson = "config";

    /**
     * @var string
     * The path to the
     * right file.
     */
    private $filePath;

    /**
     * @var object
     * JsonLoader object.
     *
     */
    private $jsonLoader;

    /**
     * @var object
     * Provides file system
     * functionality
     */
    private $file;

    /**
     *  Sets the path of the file
     */
    private function constructFilePath()
    {
        $this->filePath = TEMPLATE_PATH . "/" . $this->nameOfJson . ".json";
    }

    /**
     * Checks if given array is Multidimensional.
     * @param array $array
     * @return true/false
     */
    private function isMultiDimensionalArray(array $array)
    {
        $rv = array_filter($array, 'is_array');
        if (count($rv) > 0) return true;
        return false;
    }

    /**
     * Validates the configuration file.
     * @throws Exception
     */
    private function validateJson()
    {
        $infoTemplateArray = $this->jsonLoader->getData();

        if (!$this->isMultiDimensionalArray($infoTemplateArray))
            throw new Exception("Wrong Site Configuration File Not Multidimensional Array");

        // TODO REFACTOR IN SEPERATE FUNCTION WITH INFO WHAT IS WRONG
        if (!array_key_exists('title', $infoTemplateArray) || !array_key_exists('styles', $infoTemplateArray) || !array_key_exists('scripts', $infoTemplateArray))
            throw new Exception("Wrong Site Configuration File");
    }

    /**
     * TemplateConfigurationLoader constructor.
     * @param FileManager $file
     * @throws Exception
     */
    public function __construct(FileManager $file)
    {
        $this->file = $file;
        // construct the file path first
        $this->constructFilePath();
        // use json loader
        $this->jsonLoader = new JsonLoader($this->file, $this->filePath);

        // validate that we have the right json file
        $this->validateJson();
    }

    /**
     * Getter
     * @return mixed
     */
    public function getData()
    {
        return $this->jsonLoader->getData();
    }
}
<?php
require_once (UTILITY_PATH . "/JsonLoader.php");

/**
 *
 */
class MenuConfigurationLoader
{
    /**
     * @var string
     */
    private $nameOfJson = "menu";

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
        $this->filePath = MENU_PATH  . "/" . $this->nameOfJson . ".json";
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
        $infoMenuArray = $this->jsonLoader->getData();

        if (!$this->isMultiDimensionalArray($infoMenuArray))
            throw new Exception("Wrong Menu Configuration FileManager");

        foreach($infoMenuArray as $link)
        {
            if (!isset($link['title']) || !isset($link['id']) || !isset($link['icon']))
                throw new Exception("Wrong Menu Configuration FileManager");
        }
    }

    /**
     * LoadSiteConfiguration constructor.
     * @throws Exception
     */
    public function __construct(FileManager $file)
    {
        $this->file = $file;

        // construct the file path first
        $this->constructFilePath();

        $this->jsonLoader = new JsonLoader($file, $this->filePath);

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
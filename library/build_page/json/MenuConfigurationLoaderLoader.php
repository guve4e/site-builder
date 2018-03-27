<?php
require_once(BUILD_PATH . "/json/JsonLoader.php");

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
     * SiteConfiguration constructor.
     * @throws Exception
     */
    public function __construct(File $file)
    {
        $this->file = $file;

        // construct the file path first
        $this->constructFilePath();

        $this->jsonLoader = new JsonLoader($file, $this->filePath);
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
<?php
require_once(BUILD_PATH . "/json/JsonLoader.php");

/**
 * Loads Page json configuration
 * file.Every page has view.php file with
 * html code and page.json file with
 * configuration about the page.
 */
class ViewConfigurationLoader
{
    /**
     * @var string
     * The name of the json
     * configuration  file.
     */
    private $pageConfigFileName= "page";

    /**
     * @var string
     * The name of the page.
     */
    private $viewName;

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
        $this->filePath = VIEW_PATH . "/" . $this->viewName  . "/" . $this->pageConfigFileName . ".json";
    }

    /**
     * ViewConfigurationLoader constructor.
     * @throws Exception
     */
    public function __construct(File $file, $viewName)
    {
        $this->file = $file;
        $this->viewName = $viewName;

        // construct the file path first
        $this->constructFilePath();

        // use json loader
        $this->jsonLoader = new JsonLoader($this->file, $this->filePath);
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
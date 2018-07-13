<?php
require_once (UTILITY_PATH . "/JsonLoader.php");

/**
 * Loads Page json configuration
 * file. Every page has view.php file with
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
     * Validates the configuration file.
     * @throws Exception
     */
    private function validateJson()
    {
        $info = $this->jsonLoader->getData();

        if (!isset($info['body_class_style']) || !isset($info['title']) || !isset($info['full_screen']) ||
            !isset($info['styles']) ||  !isset($info['scripts']))
            throw new Exception("Wrong View Configuration FileManager");
    }

    /**
     * ViewConfigurationLoader constructor.
     * @throws Exception
     */
    public function __construct(FileManager $file, $viewName)
    {
        $this->file = $file;
        $this->viewName = $viewName;

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
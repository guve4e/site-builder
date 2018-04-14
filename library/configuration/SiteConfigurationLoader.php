<?php
require_once(UTILITY_PATH . "/JsonLoader.php");

/**
 * Loads site configuration json file.
 * This file is located at:
 * "/template/config.json".
 */
class SiteConfigurationLoader
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
        $this->filePath = ROOT_PATH . "/" . $this->nameOfJson . ".json";
    }

    /**
     * @param $jsonData
     * @throws Exception
     */
    private function validateSession($jsonData)
    {
        $session = !isset($jsonData->session);
        $key = !isset($jsonData->session->key);
        $time = !isset($jsonData->session->time);
        if($session || $key || $time)
            throw new Exception("Bad Session Object in Config File!");
    }

    /**
     * @param $jsonData
     * @throws Exception
     */
    private function validateCookie($jsonData)
    {
        $cookie = !isset($jsonData->cookie);
        $name = !isset($jsonData->cookie->name);
        $time = !isset($jsonData->cookie->time);

        if($cookie || $name || $time)
            throw new Exception("Bad Cookie Object in Config File!");
    }

    /**
     * @param $jsonData
     * @throws Exception
     */
    private function validateServices($jsonData)
    {
        $service = !isset($jsonData->services);
        $urlDomain = !isset($jsonData->services->url_domain);
        $urlBaseRemote = !isset($jsonData->services->url_base_remote);
        $urlBaseLocal = !isset($jsonData->services->url_base_local);

        if ($service || $urlBaseLocal || $urlDomain || $urlBaseRemote)
            throw new Exception("Bad Session Object in Config File!");
    }

    /**
     * Validates the configuration file.
     * @throws Exception
     */
    private function validateJson()
    {
        $jsonData = $this->jsonLoader->getDataAsJson();

        $this->validateServices($jsonData);
        $this->validateCookie($jsonData  );
        $this->validateSession($jsonData);

        $debug = !isset($jsonData->debug);
        $production = !isset($jsonData->production);
        $bowerUrl = !isset($jsonData->bower_url);

        if ($debug || $production || $bowerUrl)
            throw new Exception("Bad configuration file!") ;
    }

    /**
     * TemplateConfigurationLoader constructor.
     * @param File $file
     * @throws Exception
     */
    public function __construct(File $file)
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
        return $this->jsonLoader->getDataAsJson();
    }
}
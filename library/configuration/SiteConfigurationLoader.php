<?php
require_once (UTILITY_PATH . "/JsonLoader.php");

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
            throw new Exception("Bad Get Object in Config FileManager!");
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
            throw new Exception("Bad Cookie Object in Config FileManager!");
    }

    /**
     * @param $service
     * @throws Exception
     */
    private function validateWebService($service)
    {
        $serviceObject = !isset($service);
        $serviceName = !isset($service->name);
        $urlBaseRemote = !isset($service->url_base_remote);
        $urlBaseLocal = !isset($service->url_base_local);
        // API token is not mandatory

        if ($serviceObject || $serviceName || $urlBaseLocal || $urlBaseRemote)
            throw new Exception("Bad Services Object in Config FileManager!");
    }

    /**
     * @param $jsonData
     * @throws Exception
     */
    private function validateWebServices($jsonData)
    {
        if ($jsonData != false)
        {
            foreach($jsonData->web_services as $service)
                $this->validateWebService($service);
        }
    }

    /**
     * Validates the configuration file.
     * @throws Exception
     */
    private function validateJson()
    {
        $jsonData = $this->jsonLoader->getDataAsJson();

        $this->validateWebServices($jsonData);
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
        return $this->jsonLoader->getDataAsJson();
    }
}
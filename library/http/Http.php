<?php

require_once (CONFIGURATION_PATH . "/SiteConfigurationLoader.php");
require_once (HTTP_PATH . "/PhpHttpAdapter.php");

class Http
{
    private $configuration = null;
    private $webService;
    private $service;
    private $method;
    private $jsonData;
    private $parameter;
    private $mock;

    /**
     * Http constructor.
     * @throws Exception
     */
    public function __construct()
    {
        // load configuration
        $jsonLoader = new SiteConfigurationLoader(new FileManager());
        $this->configuration = $jsonLoader->getData();
    }

    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    public function setDataToSend($json_data)
    {
        $this->jsonData = $json_data;
        return $this;
    }

    public function setController(string $name)
    {
        $this->controllerName = $name;
        return $this;
    }

    public function setWebService(string $name)
    {
        $this->webService = $name;
        return $this;
    }

    public function setService(string $name)
    {
        $this->service = $name;
        return $this;
    }

    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    public function setMock()
    {
        $this->mock = true;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function send()
    {
        $rc = new PhpHttpAdapter();
        $rc->setRestCallType(new RestCall("Curl", new FileManager()))
            ->setWebServicesConfiguration($this->configuration)
            ->setWebServiceName($this->webService)
            ->setController($this->service)
            ->setMethod($this->method)
            ->setParameter($this->parameter)
            ->setDataToSend($this->jsonData);

        if ($this->mock)
            $rc->setMock();

        return $rc->send();
    }
}
<?php

/**
 * Loads configuration.
 */
final class SiteConfiguration
{
    private static $config;

    /**
     * @var static Singleton
     */
    private static $instance;

    /**
     * SiteConfiguration constructor.
     * @param JsonLoader $jsonLoader
     * @throws Exception
     */
    private function __construct(JsonLoader $jsonLoader)
    {
        if (!isset($jsonLoader)) throw new Exception("Bad argument in SiteConfiguration Constructor!");
        $this->jsonLoader = $jsonLoader;

        $jsonData = $jsonLoader->getDataAsJson();
        $this->validateConfigurationData($jsonData);

        // At this point we are sure
        // that we have good conf file
        self::$config = $jsonData;
    }

    /**
     * Singleton.
     * @param File $filejsonLoader
     * @param $viewName
     * @return SiteConfiguration
     * @throws Exception
     */
    public static function LoadSiteConfiguration(JsonLoader $jsonLoader) : StdClass
    {
        if (self::$instance === null) {
            self::$instance = new SiteConfiguration($jsonLoader);
        }

        return self::$config;
    }

    /**
     * @param object $jsonData
     * @throws Exception
     */
    private function validateConfigurationData(object $jsonData)
    {
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
}
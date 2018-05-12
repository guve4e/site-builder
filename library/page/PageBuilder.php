<?php

require_once ("Page.php");

/**
 * Interface IBuilder
 */
interface IBuilder
{
    /**
     * @return mixed
     */
    public function loadNavbar();

    /**
     * @return mixed
     */
    public function loadMenu();

    /**
     * @return mixed
     */
    public function loadView();


    /**
     * @return mixed
     */
    public function loadPageTitle();

    /**
     * @return mixed
     */
    public function buildHead();

    /**
     * @return mixed
     */
    public function buildBody();

    /**
     * @return mixed
     */
    public function printScripts();

    /**
     * @return mixed
     */
    public function loadJavaScript();

    /**
     * @return mixed
     */
    public function buildClosingTags();
}

/**
 * Class PageBuilder
 */
class PageBuilder implements IBuilder {

    /**
     * @var object
     * Provides file system
     * functionality
     */
    private $file;

    /**
     * @var null|Page
     */
    private $page = null;

    /**
     * Loads information about the site
     * from config json file
     * @throws Exception
     */
    private function loadSiteConfig() : stdClass
    {
        // load configuration
        $jsonLoader = new SiteConfigurationLoader($this->file);
        return $jsonLoader->getData();
    }

    /**
     * Loads information about the template
     * from json config file
     * @throws Exception
     */
    private function loadTemplateConfig()
    {
        // get site configuration
        $templateConfigurationLoader = new TemplateConfigurationLoader($this->file);
        return  $templateConfigurationLoader->getData();
    }

    /**
     * PageBuilder constructor.
     * @throws Exception
     */
    public function __construct() {
        $this->file = new FileManager();
        $siteConfig = $this->loadSiteConfig();
        $templateConfig = $this->loadTemplateConfig();

        $this->page = new Page($_GET, $siteConfig, $templateConfig);
    }

    /**
     * @throws Exception
     */
    public function loadNavbar() {
        $this->page->loadNavbar($this->file);
    }

    /**
     * @throws Exception
     */
    public function loadMenu() {
        $this->page->loadMenu($this->file);
    }

    /**
     * @throws Exception
     */
    public function loadView() {
        $this->page->loadView($this->file);
    }

    /**
     * @return mixed|void
     */
    public function loadPageTitle() {
        $this->page->loadPageTitle();
    }

    /**
     * @throws Exception
     */
    public function buildHead() {
        $this->page->buildHead();
    }

    /**
     * @throws Exception
     */
    public function buildBody() {
        $this->page->build($this->file);
    }

    /**
     * @throws Exception
     */
    public function printScripts() {
        $this->page->printScripts();
    }

    /**
     * @throws Exception
     */
    public function loadJavaScript() {
        $this->page->loadJavaScript($this->file);
    }

    /**
     * @return mixed|void
     */
    public function buildClosingTags()
    {
        $this->page->buildClosingTags();
    }
}
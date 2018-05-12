<?php

require_once (BUILD_PATH . "/APageBuilder.php");


class IdentificationPageBuilder extends APageBuilder
{


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
     * PageBuilder constructor.
     * @param $siteConfig
     * @param $templateConfig
     * @throws Exception
     */
    public function __construct(FileManager $file, View $view) {

        parent::__construct($file, $view);

        $siteConfig = $this->loadSiteConfig();
        $templateConfig = $this->loadTemplateConfig();

        $this->page = new Page($siteConfig, $templateConfig);
    }

    /**
     * Provides special configuration.
     * Every Builder will have its own.
     * @return mixed
     * @throws Exception
     */
    public function configure()
    {
        $this->page->setView($this->view);

        $this->page->loadNavbar($this->file);
        $this->page->loadMenu($this->file);
    }

    /**
     * Loads page title
     * @return mixed
     */
    public function loadPageTitle()
    {
        $this->page->loadPageTitle();
    }

    /**
     * Builds Header <head></head>
     * @return mixed
     * @throws Exception
     */
    public function buildHead()
    {
        $this->page->buildHead();
    }

    /**
     * Builds Body <body></body>
     * @return mixed
     * @throws Exception
     */
    public function buildBody()
    {
        $this->page->build($this->file);
    }

    /**
     * Print java script tags.
     * <script></script>
     * @return mixed
     * @throws Exception
     */
    public function printScripts()
    {
        $this->page->printScripts();
    }

    /**
     * Some views have custom JS
     * script, loaded at the bottom
     * of the page
     * @return mixed
     * @throws Exception
     */
    public function loadJavaScript()
    {
        $this->page->loadJavaScript($this->file);
    }

    /**
     * Prints closing tags.
     * </body>
     * </head>
     * @return mixed
     */
    public function printClosingTags()
    {
        $this->page->buildClosingTags();
    }
}
<?php

require_once (BUILD_PATH . "/APageBuilder.php");

class FullScreenPageBuilder extends APageBuilder
{

    /**
     * PageBuilder constructor.
     * @param $siteConfig
     * @param $templateConfig
     * @throws Exception
     */
    public function __construct(FileManager $file, View $view) {
        parent::__construct($file, $view);

        $templateConfig = $this->loadTemplateConfig();
        $this->page = new Page(null, $templateConfig);
    }

    /**
     * Provides special configuration.
     * Every Builder will have its own.
     * @return mixed
     */
    public function configure()
    {
        $this->page->setView($this->view);

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
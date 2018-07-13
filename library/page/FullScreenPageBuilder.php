<?php

require_once (BUILD_PATH . "/APageBuilder.php");

class FullScreenPageBuilder extends APageBuilder
{
    /**
     * PageBuilder constructor.
     * @param FileManager $file
     * @param View $view
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
     */
    public function configure()
    {
        $this->page->setView($this->view);

    }

    /**
     * Loads page title
     */
    public function loadPageTitle()
    {
        $this->page->loadPageTitle();
    }

    /**
     * Builds Header <head></head>
     * @throws Exception
     */
    public function buildHead()
    {
        $this->page->buildHead();
    }

    /**
     * Builds Body <body></body>
     * @throws Exception
     */
    public function buildBody()
    {
        $this->page->buildOpenTags();
        $this->page->buildView($this->file);
        $this->page->buildClosingTags();
    }

    /**
     * Print java script tags.
     * <script></script>
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
     */
    public function printClosingTags()
    {
        $this->page->buildClosingTags();
    }
}
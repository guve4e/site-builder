<?php

require_once ("Page.php");

interface IBuilder
{
    public function loadNavbar();
    public function loadMenu();
    public function loadView();
    public function loadTemplateConfig();
    public function loadSiteConfig();

    public function loadPageTitle();
    public function buildHead();

    public function buildBody();


    public function printScripts();
    public function loadJavaScript();
}

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
     * PageBuilder constructor.
     * @throws Exception
     */
    public function __construct() {
        $this->file = new FileManager();
        $this->page = new Page($_GET);
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
     * @throws Exception
     */
    public function loadTemplateConfig() {
        $this->page->loadTemplateConfig($this->file);
    }

    /**
     * @throws Exception
     */
    public function loadSiteConfig() {
        $this->page->loadSiteConfig($this->file);
    }

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
}
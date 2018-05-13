<?php
require_once ("View.php");
require_once ("Navbar.php");
require_once ("Menu.php");
require_once ("PrintHTML.php");
require_once (USER_SESSION_PATH . "/CookieSetter.php");
require_once (USER_SESSION_PATH . "/UserIdentifier.php");
require_once (CONFIGURATION_PATH . "/TemplateConfigurationLoader.php");
require_once (CONFIGURATION_PATH. "/SiteConfigurationLoader.php");


final class Page
{
    /**
     * @var object
     * View object.
     */
    private $view;

    /**
     * @var object
     * Navbar object.
     */
    private $navbar;

    /**
     * @var object
     * Menu object.
     */
    private $menu;

    /**
     * @var object
     * Loaded info from json file.
     */
    private $templateConfiguration;

    /**
     * @var stdClass
     */
    private $siteConfiguration;

    /**
     * @var string
     * Site title.
     */
    private $pageTitle;

    /**
     * Page constructor.
     * Sets the name of the view
     * @param $siteConfiguration
     * @param $templateConfiguration
     */
    public function __construct($siteConfiguration, $templateConfiguration)
    {
        $this->siteConfiguration = $siteConfiguration;
        $this->templateConfiguration = $templateConfiguration;
    }

    /**
     * Extracts the title of the pge
     * from $pageConfig member variable.
     */
    public function loadPageTitle()
    {
        $this->pageTitle = $this->templateConfiguration['title'];
    }

    /**
     * @param FileManager $file
     * @throws Exception
     */
    public function loadNavbar(FileManager $file)
    {
        $this->navbar = new Navbar($file, $this->view->getBodyClass());
    }

    public function setView(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param FileManager $file
     * @throws Exception
     */
    public function loadMenu(FileManager $file)
    {
        $this->menu = new Menu($file);
    }

    /**
     * @throws Exception
     */
    public function buildHead()
    {
        $viewStyles = $this->view->getStyles();
        $templateStyles = $this->templateConfiguration['styles'];
        PrintHTML::printHead($this->pageTitle, $this->view->getTitle(), $templateStyles, $viewStyles);
    }

    public function buildClosingTags()
    {
        PrintHTML::printClosingTags();
    }

    public function buildOpenTags()
    {
        PrintHTML::printBodyOpenTag($this->view->getBodyClass());
    }

    /**
     * @param FileManager $file
     * @throws Exception
     */
    public function buildNavbar(FileManager $file)
    {
        $path = $this->navbar->getNavbarPath();
        PrintHTML::includeHTMLPage($file, $path);
    }

    /**
     * @param FileManager $file
     * @throws Exception
     */
    public function buildMenu(FileManager $file)
    {
        $path = $this->menu->getMenuPath();
        $conf = $this->menu->getMenuConfig();
        PrintHTML::includeHTMLPage($file, $path, $conf);
    }

    /**
     * @param FileManager $file
     * @throws Exception
     */
    public function buildView(FileManager $file)
    {
        $path = $this->view->getPath();
        PrintHTML::includeHTMLPage($file, $path);
    }

    /**
     * Print Site JS scripts and
     * View JS scripts.
     * Referenced from "index.php".
     * @throws Exception
     */
    public function printScripts()
    {
        PrintHTML::printListScripts($this->templateConfiguration['scripts']);
        PrintHTML::printListScripts($this->view->getScripts());
    }

    /**
     * Every view has a "script.php"
     * file congaing a javascript
     * extending the functionality
     * of the view. This method includes
     * the "script.php" file at the bottom
     * of the view.
     * Referenced from "index.php".
     * @param FileManager $file
     */
    public function loadJavaScript(FileManager $file)
    {
        $javaScriptPath = $this->view->getViewJSPath();
        // load page javascript at the bottom
        if ($file->fileExists($javaScriptPath))
            include($this->view->getViewJSPath());
    }

    /**
     * It is called from JS, to get information
     * about the primary web service API
     * @return string json or null
     */
    public function getPrimaryWebServiceInfoForJS()
    {
        if (!isset($this->siteConfiguration->web_services))
            return null;

        $primaryWebService = $this->siteConfiguration->web_services[0];

        $config = [
            "url_base_remote" => $primaryWebService->url_base_remote,
            "url_base_local" => $primaryWebService->url_base_local
        ];

        return json_encode($config);
    }

    public function __destruct()
    {
        unset($this->file);
        unset($this->viewName);
        unset($this->view);
        unset($this->menu);
        unset($this->navbar);
        unset($this->templateConfiguration);
        unset($this->pageTitle);
    }
}
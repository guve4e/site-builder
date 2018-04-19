<?php
require_once ("View.php");
require_once ("Navbar.php");
require_once ("Menu.php");
require_once ("PrintHTML.php");
require_once (USER_SESSION_PATH . "/CookieSetter.php");
require_once (USER_SESSION_PATH . "/UserIdentifier.php");
require_once (CONFIGURATION_PATH . "/TemplateConfigurationLoader.php");
require_once (CONFIGURATION_PATH. "/SiteConfigurationLoader.php");
/**
 *
 */
final class PageBuilder
{
    /**
     * @var string
     * Represents the name of the _GET
     * super global key;
     * Ex:
     * $_GET['page'] = "home"
     * It represents 'page' string literal.
     * It can be seen in URL as query string
     * ?page=home, if its value is 'page'
     */
    private $getSuperglobalKeyName = "page";

    /**
     * @var string
     * Name of a view.
     * Initially it goes to home page.
     */
    private $viewName = "home";

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
     * @var object
     * Provides file system
     * functionality
     */
    private $file;

    /**
     * @var static Singleton
     */
    private static $instance;

    /**
     * Loads information about the site
     * store it in siteConfig object
     * at this point siteConfig contains all
     * the information needed to print the page.
     * @param File $file
     * @throws Exception
     */
    private function loadTemplateConfig(File $file)
    {
        // get site configuration
        $templateConfigurationLoader = new TemplateConfigurationLoader($file);
        $this->templateConfiguration = $templateConfigurationLoader->getData();
    }

    /**
     * Loads information about the site
     * store it in siteConfig object
     * at this point siteConfig contains all
     * the information needed to print the page.
     * @param File $file
     * @throws Exception
     */
    private function loadSiteConfig(File $file)
    {
        // load configuration
        $jsonLoader = new SiteConfigurationLoader(new File());
        $this->siteConfiguration = $jsonLoader->getData();
    }

    /**
     * Extracts the title of the pge
     * from $pageConfig member variable.
     */
    private function loadPageTitle()
    {
        $this->pageTitle = $this->templateConfiguration['title'];
    }

    /**
     * Identifies User
     * @throws Exception
     */
    private function identifyUser()
    {
        $sessionConfiguration = $this->loadSessionConfiguration();

        $cookieSetter = new CookieSetter($sessionConfiguration['cookieName'], $sessionConfiguration['cookieTime']);
        $userIdentifier = UserIdentifier::IdentifyUser($cookieSetter, new User());

        $userIdentifier->identify();
    }

    /**
     * Loads session key from
     * relative-paths.php file.
     *
     * @throws Exception
     */
    private function loadSessionConfiguration() : array
    {
        return [
            "sessionKey" => $this->siteConfiguration->session->key,
            "cookieName" => $this->siteConfiguration->cookie->name,
            "cookieTime" =>  $this->siteConfiguration->cookie->time
        ];
    }

    private function filterGet($key)
    {
        $get = [];
        foreach ($_GET as $key => $value) {
            $get[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
        }
        return $get;
    }

    /**
     * PageBuilder constructor.
     * @param File $file
     * @param array $get
     * @throws Exception
     */
    private function __construct(File $file, array $get)
    {
        if(!isset($get) || !isset($file))
            throw new Exception("Unable to construct the page wrong parameters in PageBuilder constructor!");

        // filter _GET first
        $get = $this->filterGet($this->getSuperglobalKeyName);

        // When page is loaded for first time _GET is empty
        if(isset($get[$this->getSuperglobalKeyName]))
            $this->viewName = $get[$this->getSuperglobalKeyName];

        $this->file = $file;

        $this->view = new View($file, $this->viewName);
        $this->menu = new Menu($file, $this->view->getViewName());
        $this->navbar = new Navbar($file, $this->view->getViewBodyClass());

        $this->loadTemplateConfig($file);
        $this->loadSiteConfig($file);
        $this->loadPageTitle();
    }

    /**
     * Print Site styles and
     * View styles.
     * Referenced from "index.php".
     * @throws Exception
     */
    public function printStyles()
    {
        PrintHTML::printListStyles($this->templateConfiguration['styles']);
        $this->view->printListStyles();
    }

    /**
     * TODO: Not quite!!
     * Referenced from "index.php".
     * @throws Exception
     */
    public function build()
    {
        $this->identifyUser();

        // if the view is full-screen
        // we don't want to build menu and navbar
        if (!$this->view->isFullScreen())
        {
            $this->navbar->build();
            $this->menu->build();
        }
        // we always want the view
        $this->view->build();
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
        $this->view->printListScripts();
    }

    /**
     * Every view has a "script.php"
     * file congaing a javascript
     * extending the functionality
     * of the view. This method includes
     * the "script.php" file at the bottom
     * of the view.
     * Referenced from "index.php".
     * @throws Exception
     */
    public function loadJavaScript()
    {
        $javaScriptPath = $this->view->getViewJSPath();

        // load page javascript at the bottom
        if ($this->file->fileExists($javaScriptPath))
            include($this->view->getViewJSPath());
    }

    /**
     * Wrapper around Menu::printMenu()
     */
    public function printMenu()
    {
       $this->menu->printMenu();
    }

    /**
     * Prints the title of the page
     * Combines the title of the site and
     * the title of the view
     */
    public function printTitle() {
        // get the title for the site and the title for the view
        echo $this->pageTitle . " - " . $this->view->getViewTitle();
    }

    /**
     * Getter
     * @return string: CSS class
     */
    public function getBodyClass()
    {
        return  $this->view->getViewBodyClass();
    }

    /**
     * Singleton.
     * @access public
     * @param File $file object
     * @param array $get $_GET super-global
     * @return PageBuilder
     * @throws Exception
     */
    public static function MakePage(File $file, array $get) : PageBuilder
    {
        if (self::$instance === null) {
            self::$instance = new PageBuilder($file, $get);
        }

        return self::$instance;
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

        self::$instance = null;
    }
}
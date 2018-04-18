<?php
require_once ("View.php");
require_once ("Navbar.php");
require_once ("Menu.php");
require_once ("PrintHTML.php");
require_once (CONFIGURATION_PATH . "/TemplateConfigurationLoader.php");

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
    private $siteConfig;

    /**
     * @var string
     * Site title.
     */
    private $pageTitle;

    /**
     * @var UserSession object
     */
    private $userSession;

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
    private function loadSiteConfig(File $file)
    {
        // get site configuration
        $siteConfig = new TemplateConfigurationLoader($file);
        $this->siteConfig = $siteConfig->getData();
    }

    /**
     * Extracts the title of the pge
     * from $pageConfig member variable.
     */
    private function loadPageTitle()
    {
        $this->pageTitle = $this->siteConfig['title'];
    }

    private function identifyUser()
    {
        $this->userSession->identifyUser();
    }

    /**
     * PageBuilder constructor.
     * @param File $file
     * @param UserSession $userSession
     * @param array $get
     * @throws Exception
     */
    private function __construct(File $file, UserSession $userSession, array $get)
    {
        if(!isset($get) || !isset($file) || !isset($userSession))
            throw new Exception("Unable to construct the page wrong parameters in PageBuilder constructor!");

        // When page is loaded for first time _GET is empty
        if(isset($get[$this->getSuperglobalKeyName]))
            $this->viewName = $get[$this->getSuperglobalKeyName];

        $this->file = $file;
        $this->userSession = $userSession;

        $this->view = View::MakeView($file, $this->viewName);
        $this->menu = Menu::MakeMenu($file, $this->view->getViewName());
        $this->navbar = Navbar::MakeNavbar($file, $this->view->getViewBodyClass());

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
        PrintHTML::printListStyles($this->siteConfig['styles']);
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
        PrintHTML::printListScripts($this->siteConfig['scripts']);
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
     * @param UserSession $userSession
     * @param array $get $_GET super-global
     * @return PageBuilder
     * @throws Exception
     */
    public static function MakePage(File $file, UserSession $userSession, array $get) : PageBuilder
    {
        if (self::$instance === null) {
            self::$instance = new PageBuilder($file, $userSession , $get);
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
        unset($this->siteConfig);
        unset($this->pageTitle);

        self::$instance = null;
    }
}
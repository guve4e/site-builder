<?php
require_once ("View.php");
require_once ("Navbar.php");
require_once ("Menu.php");
require_once ("PrintHTML.php");
require_once (CONSTRUCTOR_PATH . "/json/SiteConfiguration.php");
/**
 *
 */
final class Page
{


    /**
     * @var string
     * Name of a view.
     */
    private $viewName= "home";

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
    private $pageConfig;

    /**
     * @var string
     * Site title.
     */
    private $pageTitle;

    /**
     * Loads information about the site
     * store it in siteConfig object
     * at this point siteConfig contains all
     * the information needed to print the page.
     */
    private function loadSiteConfig()
    {
        // get site configuration
        $siteConfig = new SiteConfigurationLoader();
        $this->pageConfig = $siteConfig->getData();
    }

    /**
     * Extracts the title of the pge
     * from $pageConfig member variable.
     */
    private function loadPageTitle()
    {
        $this->pageTitle = $this->pageConfig['title'];
    }

    /**
     * Page constructor.
     * @param array $get
     * @param $user
     * @throws Exception
     * @return void
     */
    private function __construct(array $get)
    {
        if(!isset($get))
            throw new Exception("Unable to construct the page");

        if(isset($get['page']))
            $this->viewName = $get['page'];

        $this->view = View::MakeView($this->viewName);
        $this->menu = Menu::MakeMenu($this->view->getViewName());
        $this->navbar = Navbar::MakeNavbar($this->view->getViewBodyClass());

        $this->loadSiteConfig();

        $this->loadPageTitle();
    }

    /**
     * Print Site styles and
     * View styles.
     * Referenced from "index.php".
     */
    public function printStyles()
    {
        PrintHTML::printListStyles($this->pageConfig['styles']);
        $this->view->printListStyles();
    }

    /**
     * Sets a global variable for all the views.
     * Includes HTML for the navbar, menu and
     * the view.
     * Referenced from "index.php".
     */
    public function build()
    {
        $this->navbar->build();
        $this->menu->build();
        $this->view->build();
    }

    /**
     * Print Site JS scripts and
     * View JS scripts.
     * Referenced from "index.php".
     */
    public function printScripts()
    {
        PrintHTML::printListScripts($this->pageConfig['scripts']);
        $this->view->printListScripts();
    }

    /**
     * Every view has a "script.php"
     * file containg a javascript
     * extending the functionality
     * of the view. This method includes
     * the "script.php" file at the bottom
     * of the view.
     * Referenced from "index.php".
     */
    public function loadJavaScript()
    {
        // load page javascript at the bottom
        if (file_exists($this->view->getViewJSPath()))
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
     * Singleton.
     * @access public
     * @return Page
     */
    public static function MakePage($get) : Page
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Page($get);
        }

        return $inst;
    }

    /**
     * Getter
     * @return string: CSS class
     */
    public function getBodyClass()
    {
        $bodyClass =  $this->view->getViewBodyClass();
        return $bodyClass;
    }
}
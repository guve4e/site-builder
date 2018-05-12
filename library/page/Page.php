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
     * Loads information about the site
     * store it in siteConfig object
     * at this point siteConfig contains all
     * the information needed to print the page.
     * @param FileManager $file
     * @throws Exception
     */
    public function loadTemplateConfig(FileManager $file)
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
     * @param FileManager $file
     * @throws Exception
     */
    public function loadSiteConfig(FileManager $file)
    {
        // load configuration
        $jsonLoader = new SiteConfigurationLoader(new FileManager());
        $this->siteConfiguration = $jsonLoader->getData();
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
     * Identifies User
     * @throws Exception
     */
    public function identifyUser()
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
     * Page constructor.
     * @param FileManager $file
     * @param array $get
     * @throws Exception
     */
    public function __construct(array $get)
    {
        if(!isset($get))
            throw new Exception("Unable to construct the page wrong parameters in Page constructor!");

        // filter _GET first
        $get = $this->filterGet($this->getSuperglobalKeyName);

        // When page is loaded for first time _GET is empty
        if(isset($get[$this->getSuperglobalKeyName]))
            $this->viewName = $get[$this->getSuperglobalKeyName];

    }

    /**
     * @param FileManager $file
     * @throws Exception
     */
    public function loadNavbar(FileManager $file)
    {
        $this->navbar = new Navbar($file, $this->view->getViewBodyClass());
    }

    /**
     * @param File $file
     * @throws Exception
     */
    public function loadMenu(FileManager $file)
    {
        $this->menu = new Menu($file, $this->view->getViewName());
    }

    /**
     * @param File $file
     * @throws Exception
     */
    public function loadView(FileManager $file)
    {
        $this->view = new View($file, $this->viewName);
    }

    /**
     * @throws Exception
     */
    public function formatHead()
    {
        print "<!doctype html>\n";
        print "<!--[if lte IE 9]> <html class=\"lte-ie9\" lang=\"en\"> <![endif]-->\n";
        print "<!--[if gt IE 9]><!--> <html lang=\"en\"> <!--<![endif]-->\n";
        print "<head>\n";
        print "    <meta charset=\"UTF-8\">\n";
        print "    <meta name=\"viewport\" content=\"initial-scale=1.0,maximum-scale=1.0,user-scalable=no\">\n";
        print "    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n";
        print "    <!-- Remove Tap Highlight on Windows Phone IE -->\n";
        print "    <meta name=\"msapplication-tap-highlight\" content=\"no\"/>\n";
        print "    <link rel='stylesheet' type='text/css' href='css/navbar.css' />\n";
        print "    <link rel='stylesheet' type='text/css' href='css/sidenav.css' />\n";
        print "    <!--*** Set Title ***-->\n";
        print "    <title>{$this->pageTitle}</title>\n";

        $this->printStyles();

        print "</head>\n";
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
        print "    <body>";

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

        print "    </body>";
        print "</html>";
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
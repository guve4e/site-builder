<?php
require_once ("PrintHTML.php");
require_once(BUILD_PATH . "/json/ViewConfigurationLoaderLoader.php");
/**
 *
 */
class View
{
    /**
     * @var string
     * The path to
     * JS file for the
     * particular page.
     */
    private $viewJSPath;

    /**
     * @var string
     * The name of the page.
     * Ex: If the page file
     * is home.php,
     * $pageName is "home".
     */
    private $viewName = "";

    /**
     * @var string
     * The path to the
     * page dir.
     */
    private $viewDir = "";

    /**
     * @var string
     * The path to a
     * view.php file.
     */
    private $viewPath;

    /**
     * @var object
     * View configuration
     * loaded from json file.
     */
    private $viewConfig;

    /**
     * @var string
     * Some pages have key
     * in the query string.
     */
    private $viewKey;

    /**
     * @var string
     * CSS class.
     */
    private $viewBodyClass;

    /**
     * @var string
     * Each view has title.
     */
    private $viewTitle;

    /**
     * View constructor.
     *
     * @param $viewName
     * @throws Exception
     */
    private function __construct($viewName)
    {
        // guard clause
        if (!isset($viewName))
            throw new Exception("page name is NOT set");

        // if everything ok set the page id
        $this->viewName = $viewName;

        // seth the path
        $this->splitViewName();

        // set the view
        $this->constructViewPath();

        // set JS page
        $this->constructJSPagePath();

        $this->loadViewConfig();

        $this->setBodyClass();

        $this->setViewTitle();
    }

    /**
     * TODO SMELLS!!!
     * Given the page name, it extracts needed
     * information and initializes some attributes.
     */
    private function splitViewName() {

        // explode the name
        $view_parts = explode("-", $this->viewName, 3);

        // page_name has to be the first string
        $this->viewName = $view_parts[0];

        // check if the page has a key
        if (sizeof($view_parts) > 1) {
            $this->viewKey = $view_parts[1];
            // set page_path
            $this->viewDir = VIEW_PATH . "/" . $this->viewName . '&' . $this->viewKey;
        } else {
            // if no key
            // set page_path
            $this->viewDir = VIEW_PATH . "/" . $this->viewName;
        }
    }

    /**
     * Constructs the view path.
     */
    private function constructViewPath()
    {
        $this->viewPath = $this->viewDir . "/view.php";
        if (!file_exists($this->viewPath))
            throw new Exception("View does not exist!");
    }

    /**
     * Constructs the js page path.
     * JS script must be in the same file as the view
     * we will not check for set variable, because
     * some views would not have JS scripts
     */
    private function constructJSPagePath()
    {
        $this->viewJSPath = $this->viewDir . "/script.php";
    }

    /**
     * Loads information about the page
     * store it in page object
     * at this point viewConfig contains all
     * the information needed to print the page.
     */
    private function loadViewConfig()
    {
        $viewConfig = new ViewConfigurationLoader($this->viewName);
        $this->viewConfig = $viewConfig->getData();
    }

    /**
     * Set Body Class Style if available from
     * json config file
     */
    private function setBodyClass()
    {
        if (!isset($this->viewConfig['body_class_style']))
            throw new Exception("Wrong page json!, 'body_class_style' property not set! ");

        // extract body_class
        $this->viewBodyClass = $this->viewConfig['body_class_style'];
    }

    /**
     *
     */
    private function setViewTitle()
    {
        if (!isset($this->viewConfig['title']))
            throw new Exception("Wrong page json! 'title' property not set!");

        $this->viewTitle = $this->viewConfig['title'];
    }

    /**
     *
     */
    public function printListStyles()
    {
        if (!isset($this->viewConfig['styles']))
            throw new Exception("Wrong page json! 'styles' property not set!");

        PrintHTML::printListStyles($this->viewConfig['styles']);
    }

    /**
     *
     */
    public function printListScripts()
    {
        if (!isset($this->viewConfig['scripts']))
            throw new Exception("Wrong page json! 'scripts' property not set!");

        PrintHTML::printListScripts($this->viewConfig['scripts']);
    }

    /**
     * Includes the "*php file
     * corresponding to the view
     * and loads a script, if
     * the view has one.
     */
    public function build()
    {
        // include the view
        require($this->viewPath);
    }

    /*
     * Singleton.
     * @access public
     * @return View
     */
    public static function MakeView($viewName) : View
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new View($viewName);
        }

        return $inst;
    }

    /**
     * @return string
     */
    public function getViewName() : string
    {
        return $this->viewName;
    }

    /**
     * @return string
     */
    public function getViewBodyClass() : string
    {
        return $this->viewBodyClass;
    }

    /**
     * @return string
     */
    public function getViewPath() : string
    {
        return $this->viewPath;
    }

    /**
     * @return mixed
     */
    public function getViewTitle() : string
    {
        return $this->viewTitle;
    }

    /**
     * @return string
     */
    public function getViewJSPath() : string
    {
        return $this->viewJSPath;
    }
}
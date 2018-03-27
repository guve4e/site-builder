<?php

require_once (CONSTRUCTOR_PATH . "/Menu.php");
require_once (CONSTRUCTOR_PATH . "/json/MenuConfiguration.php");

/**
 *
 */
final class Menu
{
    /**
     * @var array
     * Array where each element
     * represents a href(link) from the menu.
     */
    private $menuConfig;

    /**
     * @var string
     * The name of the view.
     */
    private $viewName;

    /**
     * Menu constructor.
     * @param $viewName
     * @throws  Exception
     */
    private function __construct($viewName)
    {
        if(!isset($viewName))
            throw new Exception("The name of the view is not set!");

        $this->viewName = $viewName;

        $this->loadMenuConfig();
    }

    /**
     * Loads json configuration.
     */
    private function loadMenuConfig()
    {
        $menuConfig = new MenuConfigurationLoader();
        $this->menuConfig = $menuConfig->getData();
    }

    /**
     * Includes the "*php file
     * corresponding to the menu.
     */
    public function build()
    {
        include(MENU_PATH . '/sidebar_main.php');
    }

    /**
     * Prints the menu on the left side.
     * It is a wrapper to printOneLink.
     * Uses info stored in $menuConfig.
     */
    public function printMenu() {

        // iterate trough each item and display it
        foreach ($this->menuConfig as $m) {
            PrintHTML::printOneLink($m, $this->viewName);
        }
    }

    /**
     * Singleton.
     * @access public
     * @return Menu
     */
    public static function MakeMenu($viewName) : Menu
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Menu($viewName);
        }

        return $inst;
    }
}
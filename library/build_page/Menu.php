<?php

require_once (BUILD_PATH . "/Menu.php");
require_once (BUILD_PATH . "/json/MenuConfigurationLoaderLoader.php");

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
     * Menu constructor.
     * @param File $file
     * @param string $viewName
     * @throws Exception
     */
    private function __construct(File $file, string $viewName)
    {
        if(!isset($viewName) || !isset($file))
            throw new Exception("The name of the view is not set!");

        $this->file = $file;
        $this->viewName = $viewName;

        $this->loadMenuConfig();
    }

    /**
     * Loads json configuration.
     * @throws Exception
     */
    private function loadMenuConfig()
    {
        $menuConfig = new MenuConfigurationLoader($this->file);
        $this->menuConfig = $menuConfig->getData();
    }

    /**
     * Includes the "*php file
     * corresponding to the menu.
     * @throws Exception
     */
    public function build()
    {
        $menuPath = MENU_PATH . '/sidebar_main.php';

        // include the menu
        if ($this->file->fileExists($menuPath))
            include($menuPath);
        else
            throw new Exception("Menu can not be build '{$menuPath}' does not exist!");
    }

    /**
     * Prints the menu on the left side.
     * It is a wrapper to printOneLink.
     * Uses info stored in $menuConfig.
     */
    public function printMenu()
    {
        // iterate trough each item and display it
        foreach ($this->menuConfig as $m) {
            PrintHTML::printOneLink($m, $this->viewName);
        }
    }

    /**
     * Singleton.
     * @access public
     * @return Menu
     * @throws Exception
     */
    public static function MakeMenu(File $file, $viewName) : Menu
    {
        if (self::$instance === null) {
            self::$instance = new Menu($file, $viewName);
        }

        return self::$instance;
    }

    public function __destruct()
    {
        unset($this->file);
        unset($this->menuConfig);
        unset($this->viewName);

        self::$instance = null;
    }
}
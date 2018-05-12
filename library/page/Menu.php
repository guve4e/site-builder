<?php

require_once (BUILD_PATH . "/Menu.php");
require_once (CONFIGURATION_PATH . "/MenuConfigurationLoader.php");

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
     * Menu constructor.
     * @param FileManager $file
     * @param string $viewName
     * @throws Exception
     */
    public function __construct(FileManager $file, string $viewName)
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
        $menuPath = MENU_PATH . '/menu.php';

        // include the menu
        if (!$this->file->fileExists($menuPath))
            throw new Exception("Menu can not be build '{$menuPath}' does not exist!");
        include($menuPath);
    }

    /**
     * Prints the menu on the left side.
     * It is a wrapper to printOneLink.
     * Uses info stored in $menuConfig.
     */
    public function getMenuConfig() : array
    {
        return $this->menuConfig;
    }

    public function __destruct()
    {
        unset($this->file);
        unset($this->menuConfig);
        unset($this->viewName);
    }
}
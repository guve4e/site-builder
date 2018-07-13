<?php

require_once (BUILD_PATH . "/Menu.php");
require_once (CONFIGURATION_PATH . "/MenuConfigurationLoader.php");

final class Menu
{
    /**
     * @var array
     * Array where each element
     * represents a href(link) from the menu.
     */
    private $menuConfig;

    /**
     * @var object
     * Provides file system
     * functionality
     */
    private $file;

    /**
     * Menu constructor.
     * @param FileManager $file

     * @throws Exception
     */
    public function __construct(FileManager $file)
    {
        if(!isset($file))
            throw new Exception("File Manager not set in Menu!");

        $this->file = $file;

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
    public function getMenuPath()
    {
        return MENU_PATH . '/menu.php';
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
    }
}
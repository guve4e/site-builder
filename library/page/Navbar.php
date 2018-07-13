<?php

/**
 * Represents the Navbar of the web page.
 *
 * Template specific!
 *
 * If the page body class is "header_full",
 * the page is able to have secondary sidebar,
 * if not, the page must not have secondary sidebar.
 */
final class Navbar
{
    /**
     * @var string
     * The name of the navbar file.
     */
    private $navbarFileName = "navbar";

    /**
     * @var boolean
     * Some pages have secondary
     * slide-bar.
     */
    private $hasSecondarySidebar;

    /**
     * @var string
     * CSS class
     */
    private $bodyClass;

    /**
     * @var object
     * Provides file system
     * functionality
     */
    private $file;

    /**
     * Decide if the page will have secondary sidebar
     */
    private function setBodyClassStyle()
    {
        if ($this->bodyClass == 'header_full')
            $this->hasSecondarySidebar = true;
        else
            $this->hasSecondarySidebar = false;
    }

    /**
     * Navbar constructor.
     * @param FileManager $file object
     * @param $bodyClass
     * @throws Exception
     */
    public function __construct(FileManager $file, string $bodyClass)
    {
        if(!isset($file) || !isset($bodyClass))
            throw new Exception("The name of the view is not set!");

        $this->file = $file;

        // extract body_class
        $this->bodyClass = $bodyClass;

        $this->setBodyClassStyle();
    }

    public function getNavbarPath()
    {
        return $path = NAVBAR_PATH . "/{$this->navbarFileName}.php";
    }

    /**
     * @return bool
     */
    public function hasSecondarySidebar(): bool
    {
        return $this->hasSecondarySidebar;
    }

    public function __destruct()
    {
        unset($this->file);
        unset($this->bodyClass);
    }
}
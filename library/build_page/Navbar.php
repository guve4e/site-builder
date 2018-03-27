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
     * @param $bodyClass
     *
     */
    private function __construct($bodyClass)
    {
        // extract body_class
        $this->bodyClass = $bodyClass;
        $this->setBodyClassStyle();
    }


    /**
     * Includes the "*php" file
     * corresponding to the navbar.
     */
    public function build()
    {
        include(NAVBAR_PATH . '/header_main.php');
        if ($this->hasSecondarySidebar)
            include(TEMPLATE_PATH . '/second_sidebar.php');
    }

    /**
     * Singleton.
     * @access public
     * @return Navbar
     */
    public static function MakeNavbar($bodyClass) : Navbar
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Navbar($bodyClass);
        }

        return $inst;
    }
}
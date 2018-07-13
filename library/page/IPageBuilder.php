<?php

require_once ("Page.php");

/**
 * Interface IBuilder
 */
interface IPageBuilder
{
    /**
     * Provides special configuration.
     * Every Builder will have its own.
     */
    public function configure();

    /**
     * Loads page title
     */
    public function loadPageTitle();

    /**
     * Builds Header <head></head>
     */
    public function buildHead();

    /**
     * Builds Body <body></body>
     */
    public function buildBody();

    /**
     * Print java script tags.
     * <script></script>
     */
    public function printScripts();

    /**
     * Some views have custom JS
     * script, loaded at the bottom
     * of the page
     */
    public function loadJavaScript();

    /**
     * Prints closing tags.
     * </body>
     * </head>
     */
    public function printClosingTags();
}
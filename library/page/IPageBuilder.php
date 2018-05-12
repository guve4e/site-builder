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
     * @return mixed
     */
    public function configure();

    /**
     * Loads page title
     * @return mixed
     */
    public function loadPageTitle();

    /**
     * Builds Header <head></head>
     * @return mixed
     */
    public function buildHead();

    /**
     * Builds Body <body></body>
     * @return mixed
     */
    public function buildBody();

    /**
     * Print java script tags.
     * <script></script>
     * @return mixed
     */
    public function printScripts();

    /**
     * Some views have custom JS
     * script, loaded at the bottom
     * of the page
     * @return mixed
     */
    public function loadJavaScript();

    /**
     * Prints closing tags.
     * </body>
     * </head>
     * @return mixed
     */
    public function printClosingTags();
}
<?php

class PageBuilderFactory
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
     * @var View Object
     */
    private $view;

    /**
     * @var object
     * Provides file system
     * functionality
     */
    private $file;

    /**
     * Filters _GET super-global
     * @param $key
     * @return array
     */
    private function filterGet($key)
    {
        $get = [];
        foreach ($_GET as $key => $value) {
            $get[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
        }
        return $get;
    }

    /**
     * @param File $file
     * @throws Exception
     */
    private function loadView()
    {
        $this->view = new View($this->file, $this->viewName);
    }

    /**
     * PageBuilderFactory constructor.
     * @throws Exception
     */
    private function __construct(FileManager $file, array $get)
    {
        if(!isset($file) || !isset($get))
            throw new Exception("Unable to construct PageBuilder, wrong parameters in constructor!");

        $this->file = $file;

        // filter _GET first
        $get = $this->filterGet($this->getSuperglobalKeyName);

        // when page is loaded for first time _GET is empty
        if(isset($get[$this->getSuperglobalKeyName]))
            $this->viewName = $get[$this->getSuperglobalKeyName];
        // else viewName has default value

        $this->loadView();
    }

    /**
     * @return FullScreenPageBuilder|IdentificationPageBuilder
     * @throws Exception
     */
    private function getPageBuilder()
    {
        // if view is full screen make fullScreen Builder
        if ($this->view->isFullScreen())
        {
            return new FullScreenPageBuilder($this->file, $this->view);
        } else { // else, make normal IdentificationBuilder
            return new IdentificationPageBuilder($this->file, $this->view);
        }
    }

    /**
     * @access public
     * @return IPageBuilder
     * @throws Exception
     */
    public static function MakePageBuilder(FileManager $file, array $get) : IPageBuilder
    {
        $builderFactory = new PageBuilderFactory($file, $get);
        return $builderFactory->getPageBuilder();
    }
}
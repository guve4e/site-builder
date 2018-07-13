<?php

abstract class APageBuilder implements IPageBuilder
{
    /**
     * @var object
     * Provides file system
     * functionality
     */
    protected $file;

    /**
     * @var null|Page
     */
    protected $page = null;

    /**
     * @var View Object
     * Every Page has View
     */
    protected $view;

    /**
     * PageBuilder constructor.
     * @param $siteConfig
     * @param $templateConfig
     * @throws Exception
     */
    protected function __construct(FileManager $file, View $view)
    {
        if (!isset($file) || !isset($view))
            throw new Exception("Bad parameters in FullScreenPageBuilder constructor!");

        $this->file = $file;
        $this->view = $view;
    }

    /**
     * Loads information about the template
     * from json config file
     * @throws Exception
     */
    protected function loadTemplateConfig()
    {
        // get site configuration
        $templateConfigurationLoader = new TemplateConfigurationLoader($this->file);
        return  $templateConfigurationLoader->getData();
    }
}
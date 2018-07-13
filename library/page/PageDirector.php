<?php

require_once ("IPageDirector.php");

/**
 * Class PageDirector
 */
class PageDirector implements IPageDirector {

    /**
     * @var IPageBuilder|null
     */
    private $builder = null;

    /**
     * PageDirector constructor.
     * @param IPageBuilder $builder
     */
    public function __construct(IPageBuilder $builder) {
        $this->builder = $builder;
    }

    /**
     * @return mixed|void
     */
    public function buildPage() {

        // load configurations
        $this->builder->configure();
        $this->builder->loadPageTitle();

        // build and print html page
        $this->builder->buildHead();
        $this->builder->buildBody();
        $this->builder->printScripts();
        $this->builder->loadJavaScript();
        $this->builder->printClosingTags();
    }
}

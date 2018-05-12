<?php

/**
 * Interface IPageDirector
 */
interface IPageDirector
{
    /**
     * IPageDirector constructor.
     * @param IBuilder $builder
     */
    public function __construct(IBuilder $builder);

    /**
     * @return mixed
     */
    public function buildPage();
}

/**
 * Class PageDirector
 */
class PageDirector implements IPageDirector {

    /**
     * @var IBuilder|null
     */
    private $builder = null;

    /**
     * PageDirector constructor.
     * @param IBuilder $builder
     */
    public function __construct(IBuilder $builder) {
        $this->builder = $builder;
    }

    /**
     * @return mixed|void
     */
    public function buildPage() {

        // load configurations
        $this->builder->loadView();
        $this->builder->loadMenu();
        $this->builder->loadNavbar();
        $this->builder->loadPageTitle();

        // build and print html page
        $this->builder->buildHead();
        $this->builder->buildBody();
        $this->builder->printScripts();
        $this->builder->loadJavaScript();
        $this->builder->buildClosingTags();
    }
}

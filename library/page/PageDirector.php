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
    private $builder = NULL;

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


        $this->builder->loadView();
        $this->builder->loadMenu();
        $this->builder->loadNavbar();

        $this->builder->loadTemplateConfig();
        $this->builder->loadSiteConfig();

        $this->builder->loadPageTitle();

        $this->builder->formatHead();

        $this->builder->build();

        $this->builder->printScripts();
        $this->builder->loadJavaScript();
    }
}

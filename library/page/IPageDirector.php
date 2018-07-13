<?php

/**
 * Interface IPageDirector
 */
interface IPageDirector
{
    /**
     * IPageDirector constructor.
     * @param IPageBuilder $builder
     */
    public function __construct(IPageBuilder $builder);

    /**
     * @return mixed
     */
    public function buildPage();
}
<?php

class FormHandler
{
    private $navigateTo;
    private $entity;
    private $verb;
    private $parameter;
    private $navigateAfterUpdate;
    private $pathSuccess;
    private $pathFail;
    private $formActionString;

    /**
     * @throws Exception
     */
    public function validateComponents()
    {
        if (!isset($this->verb) || !isset($this->entity) || !isset($this->navigateAfterUpdate) ||
            !isset($this->pathFail) || !isset($this->pathSuccess) || !isset($this->navigateTo))
            throw new Exception("Not All Form Action Components are set!");
    }

    /**
     * @throws Exception
     */
    private function generateFormActionString()
    {
        $this->validateComponents();

        $this->formActionString = $this->navigateTo .
            "?entity={$this->entity}&verb={$this->verb}&parameter={$this->parameter}" .
            "&navigate={$this->navigateAfterUpdate}&path_success={$this->pathSuccess}" .
            "&path_fail={$this->pathFail}";
    }

    /**
     * @param mixed $entity
     * @return FormHandler
     */
    public function setEntity($entity): FormHandler
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param mixed $verb
     * @return FormHandler
     */
    public function setVerb($verb): FormHandler
    {
        $this->verb = $verb;
        return $this;
    }

    /**
     * @param mixed $parameter
     * @return FormHandler
     */
    public function setParameter($parameter): FormHandler
    {
        $this->parameter = $parameter;
        return $this;
    }

    /**
     * @param mixed $navigateAfterUpdate
     * @return FormHandler
     */
    public function setNavigateAfterUpdate($navigateAfterUpdate): FormHandler
    {
        $this->navigateAfterUpdate = $navigateAfterUpdate;
        return $this;
    }

    /**
     * @param mixed $pathSuccess
     * @return FormHandler
     */
    public function setPathSuccess($pathSuccess): FormHandler
    {
        $this->pathSuccess = $pathSuccess;
        return $this;
    }

    /**
     * @param mixed $pathFail
     * @return FormHandler
     */
    public function setPathFail($pathFail): FormHandler
    {
        $this->pathFail = $pathFail;
        return $this;
    }

    /**
     * @param mixed $navigateTo
     * @return FormHandler
     */
    public function navigateTo($navigateTo): FormHandler
    {
        $this->navigateTo = $navigateTo . ".php";
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormActionString()
    {
        return $this->formActionString;
    }

    /**
     * @throws Exception
     */
    public function printFormAction()
    {
        $this->generateFormActionString();
        echo $this->formActionString;
    }
}
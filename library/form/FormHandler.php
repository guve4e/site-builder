<?php

class FormHandler
{
    /**
     * @var string
     */
    private $navigateTo;

    /**
     * @var string
     */
    private $entity;

    /**
     * @var string
     */
    private $verb;

    /**
     * @var string
     */
    private $parameter;

    /**
     * @var string
     */
    private $navigateAfterUpdate;

    /**
     * @var string
     */
    private $pathSuccess;

    /**
     * @var string
     */
    private $pathFail;

    /**
     * @var string
     */
    private $pathSuccessParams = "";

    /**
     * @var string
     */
    private $pathFailParams = "";

    /**
     * @var string
     */
    private $formActionString;

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

        if ($this->pathSuccessParams != "")
            $this->formActionString .= "&path_success_params={$this->pathSuccessParams}";

        if ($this->pathFailParams != "")
            $this->formActionString .="&path_fail_params={$this->pathFailParams}";
    }

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

    public function setPathSuccessParameters(string $params): FormHandler
    {
        $this->pathSuccessParams = $params;
        return $this;
    }

    public function setPathFailParameters(string $params): FormHandler
    {
        $this->pathFailParams = $params;
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
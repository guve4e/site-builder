<?php

/**
 * Data class that abstracts the
 * low level detail of extracting information
 * from $_GET and $_POST send to form-handler.php
 * Extract information from html form.
 */
final class ServiceForm
{
    /**
     * @var string
     * The name of the view.
     * Represents a name of the folder
     * where the view is stored
     */
    private $view;

    /**
     * @var string
     * The name of the sub-view.
     * Represents a name of a part
     * of the view from where the
     * request is sent
     */
    private $subView;

    /**
     * @var string
     * The name of the form,
     * the request will be send
     */
    private $serviceName;

    /**
     * @var array
     * Represent the fields
     * collected from the form
     */
    private $fields = array();

    /**
     * @var string
     * Optional attribute.
     * If not set initial value is null.
     * Represent key, value query parameter
     * as: &key=value.
     * Used if after the request, the page needs
     * to be redirected to place where parameter
     * is needed.
     * Example: ./?page=shoppingcart&product=2
     * Here $paramName represents "product"
     * from the query string.
     */
    private $queryStringParamName = null;

    /**
     * @var string
     * Optional attribute.
     * If not set initial value is null.
     * Represent key, value query parameter
     * as: &key=value.
     * Used if after the request, the page needs
     * to be redirected to place where parameter
     * is needed.
     * Example: ./?page=shoppingcart&product=2
     * Here $paramValue represents "2"
     * from the query string.
     */
    private $queryStringParamValue = null;

    /**
     * @var string
     * Optional attribute.
     * If not set initial value is null.
     * Represent the request GET parameter.
     * Example: request to example.com/someservice/123
     * Here $requestParameter represents "123"
     */
    private $requestParameter = null;

    /**
     * Validates if the super-globals $_GET and $_POST
     * contain the right information.
     *
     * @param $get: representing the $_GET super-global
     * @param $fields: representing the $_POST super-global
     * @throws Exception
     */
    private function validateAttributes(array $get, array $fields)
    {
        if(!isset($get['view']) || !isset($get["subView"]) || !isset($get['from']) ||  !isset($fields))
            throw new Exception("Invalid Arguments in CollectInfo.");
        if(!is_array($fields))
            throw new Exception("Fields attribute is not of type array.");
    }

    /**
     * Retrieves the right information from the $_GET
     * super-global and initializes $requestParameter,
     * if such info is provided. If the info is not
     * provided, the attribute remains null.
     *
     * @param $get: representing the $_GET super-global
     */
    private function setRequestParameter(array $get)
    {
        if(isset($get['requestParam']))
            $this->requestParameter = $get['requestParam'];
    }

    /**
     * Retrieves the right information from the $_GET
     * super-global and initializes $queryStringParamName
     * and queryStringParamValue if such info is provided.
     * If the info is not provided, the attributes remain null.
     *
     * @param $get: representing the $_GET super-global
     */
    private function setQueryStringParameter(array $get)
    {
        if(isset($get['paramName'])) $this->queryStringParamName = $get['paramName'];
        if(isset($get['paramValue'])) $this->queryStringParamValue = $get['paramValue'];
    }

    /**
     * ServiceForm constructor.
     *
     * @param array $get : representing $_GET
     * @param array $fields : representing $_POST
     * @throws Exception
     */
    public function __construct(array $get, array $fields)
    {
        $this->validateAttributes($get, $fields);

        // Now we know that _GET is proper
        $this->view = $get['view'];
        $this->subView = $get["subView"];
        $this->serviceName = $get['from'];
        $this->fields = $fields;

        // optional attributes initialization

        // Check if there is a query string parameter
        $this->setQueryStringParameter($get);

        // check if there is a request parameter
        $this->setRequestParameter($get);
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return mixed
     */
    public function getSubView()
    {
        return $this->subView;
    }

    /**
     * @return mixed
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return null
     */
    public function getQueryStringParamName()
    {
        return $this->queryStringParamName;
    }

    /**
     * @return null
     */
    public function getQueryStringParamValue()
    {
        return $this->queryStringParamValue;
    }

    /**
     * @return null
     */
    public function getRequestParameter()
    {
        return $this->requestParameter;
    }

}
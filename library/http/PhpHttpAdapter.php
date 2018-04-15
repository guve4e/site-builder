<?php

require_once ("phphttp/RestCall.php");

/**
 * Wrapper around RestCall.
 * Hides unnecessary information from
 * client: content type, base url, etc.
 */

class PhpHttpAdapter {

    /**
     * @var string
     * represent URL
     */
    private $url = null;

    /**
     * @var string
     * VERB
     */
    private $method;

    /**
     * @var string
     * Controller Name
     */
    private $controllerName;

    /**
     * @var mixed
     * Controller parameter
     */
    private $parameter;

    /**
     * If Rest Call is Mock or not
     * @var boolean
     */
    private $isMock = false;

    /**
     * @var mixed
     * Data to send
     */
    private $jsonData = null;

    /**
     * @var
     * MIME type
     */
    private $contentType = "";

    /**
     * @var bool
     * Indicated if the RestCall
     * Was successful and the
     * database update was successful
     */
    private $success;

    /**
     * httpphp object
     * used to send HTTP
     * requests to back-end form.
     * @var
     */
    private $restCall;

    /**
     * @var array associative
     */
    private $webService = [];

    /**
     * @var array multidimensional
     */
    private $webServices = [];

    /**
     * Constructs Mock URL
     * @return string as a path
     * @throws Exception
     */
    private function mockServiceUrl() : string
    {
        $path = $this->webService['url_base_local'];
        $path .= "/" . $this->controllerName . ".json";

        return $path;
    }

    /**
     * Figures out the content type.
     * @param $method
     * @throws Exception
     */
    private function configureContentType(string $method)
    {
        if(!isset($method)) throw new Exception("Method not set!");

        if($method == "GET")
            $this->contentType = "application/x-www-form-urlencoded";
        else
            $this->contentType = "application/json";
    }

    /**
     * Constructs the URL used for RestCall
     * @throws Exception
     */
    private function constructUrl()
    {
        if (!$this->isMock)
        {
            $base = $this->webService['url_base_remote'];
            $url = $base . "/" . $this->controllerName;

            if(isset($this->parameter))
                $url .= "/" . $this->parameter;

            $this->url = $url;
        } else {
            $this->url = $this->mockServiceUrl();
        }
    }

    private function determineSuccess(RestResponse $res)
    {
        // TODO
        $this->success = true;

//        if (!isset($res) || !$res->isSuccessful())
//            $this->success = false;
//
//
//        if (!isset($this->jsonData->success))
//            $this->success = false;
//        else
//            $this->success = $this->jsonData->success;
//
    }

    /**
     * Sends the Request.
     *
     * Wrapper aground RestRequest::http_send
     * @return RestResponse
     * @throws Exception
     */
    private function httpSend() : RestResponse
    {
        $this->restCall->setUrl($this->url)
            ->setContentType($this->contentType)
            ->setMethod($this->method);

        // If client didn't specify body
        if (!is_null($this->jsonData))
            $this->restCall->addBody($this->jsonData);

        // security header
        $headers = [
            "ApiToken" => $this->webService['api_token']
        ];

        $this->restCall->setHeaders($headers);
        $this->restCall->send();
        $request = $this->restCall->getResponseWithInfo();

        $this->determineSuccess($request);

        return $request;
    }

    /**
     * @param array $arr
     * @param $key
     * @param $value
     * @return array|bool|mixed
     * @throws Exception
     */
    private function searchInMultidimensionalArray(array $arr, $key, $value)
    {
       // base case
        if (array_key_exists($key, $arr))
        {
            if ($arr[$key] == $value)
                return $arr;
        }
        foreach($arr as $element)
        {
            if (is_array($element))
            {
                if ($this->searchInMultidimensionalArray($element, $key, $value))
                    return $element;
            }
        }
        // if not found return false
        return false;
    }

    /**
     * @param RestCall $restCall
     * @return PhpHttpAdapter
     * @throws Exception
     */
    public function setRestCallType(RestCall $restCall)
    {
        if(!isset($restCall))
            throw new Exception("Bad parameter in setRestCallType!");

        $this->restCall = $restCall;
        return $this;
    }

    /**
     * @param StdClass $configuration
     * @return PhpHttpAdapter
     * @throws Exception
     */
    public function setWebServicesConfiguration(StdClass $configuration)
    {
        if(!isset($configuration))
            throw new Exception("Bad parameter in setWebServicesConfiguration!");

        $this->webServices = json_decode(json_encode($configuration->web_services), true);
        return $this;
    }

    public function setMock()
    {
        $this->isMock = true;
        return $this;
    }

    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    /**
     * Sets Web Service Name
     * @param string $name
     * @return $this
     * @throws Exception
     */
    public function setWebServiceName(string $name)
    {
        // first check if configuration is set up
        if (empty($this->webServices))
            throw new Exception("You must specify a configuration first!");

        // search fot web service with the provided name
        $arr = $this->searchInMultidimensionalArray($this->webServices, "name" ,$name);

        if (!$arr)
            throw new Exception("There is no such web service in the configuration file!");

        // if found assign it to property
        $this->webService = $arr;
        return $this;
    }

    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    public function setDataToSend(array $json_data)
    {
        $this->jsonData = $json_data;
        return $this;
    }

    public function setController(string $name)
    {
        $this->controllerName = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->success;
    }

    /**
     * @return object
     * @throws Exception
     */
    public function send()
    {
        $this->configureContentType($this->method);
        $this->constructUrl();
        $response = $this->httpSend();

        if ($this->success)
            return $response->getBodyAsJson();
        else
            throw new Exception("Unsuccessful API Call!");
    }
}

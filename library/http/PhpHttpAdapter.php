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
     * API token
     */
    private $api_token = "WRCdmach38E2*$%Ghdo@nf#cOBD4fd";

    /**
     * @var string
     * represent URL
     */
    private $url = null;

    /**
     * @var
     */
    private $method;

    /**
     * @var
     * Controller Name
     */
    private $serviceName;

    /**
     * @var
     * Controller get Parameter
     */
    private $parameter;

    /**
     * If Rest Call is Mock or not
     * @var
     */
    private $isMock = false;

    /**
     * @var
     * Data to send
     */
    private $jsonData = null;

    /**
     * @var
     * MIME type
     */
    private $contentType = null;

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
     * requests to back-end services.
     * @var
     */
    private $restCall;

    /**
     * @var StdClass
     */
    private $configuration;

    /**
     * Get Mock URL
     * @return string as a path
     * @throws Exception
     */
    private function mockServiceUrl() : string
    {

        $path = $this->configuration->services->url_base_local; // get path from config file
        $path .= "/" . $this->serviceName . ".json";

        return $path;
    }

    /**
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
            $base = $this->configuration->services->url_base_local;

            $url = $base . "/" . $this->serviceName;

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
            "ApiToken" => $this->api_token
        ];

        $this->restCall->setHeaders($headers);
        $this->restCall->send();
        $request = $this->restCall->getResponseWithInfo();

        $this->determineSuccess($request);

        return $request;
    }

    /**
     * PhpHttpAdapter constructor.
     * @param RestCall $restCall
     * @throws Exception
     */
    public function __construct(RestCall $restCall, StdClass $configuration)
    {
        if(!isset($restCall) || !isset($configuration))
            throw new Exception("Bad parameter in PhpHttpAdapter!");

        $this->restCall = $restCall;
        $this->configuration = $configuration;
    }

    /**
     *
     * @return $this
     */
    public function setMock()
    {
        $this->isMock = true;
        return $this;
    }

    /**
     * @param mixed $parameter
     * @return $this
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    /**
     * @param mixed $serviceName
     * @return $this
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    /**
     * @param mixed $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param mixed $json_data
     * @return $this
     */
    public function setJsonData($json_data)
    {
        $this->jsonData = $json_data;
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

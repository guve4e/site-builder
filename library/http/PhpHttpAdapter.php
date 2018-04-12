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
     * Get Mock URL
     * @return string as a path
     */
    private function mockServiceUrl() : string
    {
        global $config;
        $path = $config['service']['base-local']; // get path from config file
        return $path . "/" . $this->serviceName . ".json";
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
     */
    private function constructUrl()
    {
        if ($this->isMock) return $this->mockServiceUrl();

        global $config;
        $base = $config['services']['base'];

        $url = $base . "/" . $this->serviceName;

        if(isset($this->parameter))
            $url .= "/" . $this->parameter;

        $this->url = $url;
    }

    /**
     * @return RestCall
     * @throws Exception
     */
    private function constructRestCall()
    {
        return new RestCall("Curl", new File());
    }

    /**
     * Sends the Request.
     *
     * Wrapper aground RestRequest::http_send
     * @return string
     */
    private function http_send()
    {

        $restCall = $this->constructRestCall();
        $restCall->setUrl($this->url);
        $restCall->setContentType($this->contentType);
        $restCall->setMethod($this->method);

        if (!is_null($this->jsonData))
            $restCall->setJsonData($this->jsonData);

        // security header
        $headers = [
            "ApiToken" => $this->api_token
        ];

        $restCall->setHeaders($headers);

//        Logger::log_http_send($this->method,$this->contentType,$this->url,$this->jsonData);

        // make the call
        $request = $restCall->send();

        $this->jsonData = json_decode($request->getBody());

        // Log the data received
//        Logger::log_http_receive($request);


        $this->determineSuccess($request);


        return $request;
    }



    /**
     * @return mixed
     */
    public function getJsonData()
    {
        return $this->jsonData;
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

    public function send()
    {
        $this->configureContentType($this->method);
        $this->constructUrl();
        $this->http_send();

        if ($this->success)
            return $this->jsonData;
        else
            throw new Exception("Unsuccessfull API Call!");
    }

    private function determineSuccess(RestResponse $res)
    {
        $this->success = true;

        if (!isset($res) || !$res->isSuccessful())
            $this->success = false;


        if (!isset($this->jsonData->success))
            $this->success = false;
        else
            $this->success = $this->jsonData->success;
    }
}

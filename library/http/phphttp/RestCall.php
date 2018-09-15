<?php

require_once("HttpCurlCall.php");
require_once("HttpSocketCall.php");
require_once(UTILITY_PATH . "/FileManager.php");

class RestCall
{
    /**
     * @var null|HttpSocketCall
     */
    private $strategy = null;

    /**
     * RestCall constructor.
     * @param string $restCallType
     * @throws Exception
     */
    public function __construct(string $restCallType, FileManager $file)
    {
        switch ($restCallType)
        {
            case "Curl":
                $this->strategy = new HttpCurlCall();
                break;
            case "HttpSocket":
                $this->strategy = new HttpSocketCall($file);
                break;
        }
    }

    /**
     * @param string $url
     * @return $this
     * @throws Exception
     */
    public function setUrl(string $url) {
         $this->strategy->setUrl($url);
         return $this;
    }

    /**
     * @param string $method
     * @return $this
     * @throws Exception
     */
    public function setMethod(string $method) {
        $this->strategy->setMethod($method);
        return $this;
    }

    /**
     * @param string $contentType
     * @return $this
     * @throws Exception
     */
    public function setContentType(string $contentType) {
        $this->strategy->setContentType($contentType);
        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers) {
        $this->strategy->setHeaders($headers);
        return $this;
    }

    /**
     * @param string $fieldName
     * @param string $fieldValue
     * @return mixed
     */
    public function addHeader(string $fieldName, string $fieldValue) {
        $this->strategy->addHeader($fieldName, $fieldValue);
        return $this;
    }

    /**
     * @param array $jsonData
     * @return $this
     * @throws Exception
     */
    public function addBody(array $jsonData) {
        $this->strategy->addBody($jsonData);
        return $this;
    }

    /**
     * @return mixed|void
     * @throws Exception
     */
    public function send() {
       $this->strategy->send();
    }

    /**
     * @param bool $waiting
     */
    public function isWaitingForResponse(bool $waiting) {
        return $this->strategy->isWaitingForResponse($waiting);
    }

    /**
     * @return mixed|string
     */
    public function getResponseRaw() {
        return $this->strategy->getResponseRaw();
    }

    /**
     * @return mixed
     */
    public function getResponseAsString() {
        return $this->strategy->getResponseAsString();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getResponseAsJson() {
        return $this->strategy->getResponseAsJson();
    }

    /**
     * @return mixed|RestResponse
     */
    public function getResponseWithInfo() {
        return $this->strategy->getResponseWithInfo();
    }
}
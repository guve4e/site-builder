<?php

require_once ("SocketCall.php");

class HttpSocketCall extends AHttpRequest
{
    /**
     * @var string
     * Host extracted from url.
     * Ex: http://house-net.ddns.net/secure/index.html
     * host =  http://house-net.ddns.net
     */
    private $host;

    /**
     * @var string
     * Path extracted from url.
     * Ex: http://house-net.ddns.net/secure/index.html
     * path = /secure/index.html
     */
    private $path;

    /**
     * @var FileManager
     * Provides file system
     * functionality
     */
    private $file;

    /**
     * @var bool
     * If set to true, send() method
     * will wait for server response.
     * If not, it will send the request
     * and continue without waiting for
     * response.
     */
    private $isWaitingForResponse = true;

    /**
     * Extracts http code from header.
     * @param string $header the header line
     * @return string http code
     * @throws Exception
     */
    private function retrieveCode(string $header) : string
    {
        if (!isset($header))
            throw new Exception("Wrong input in retrieve Code!");

        $parts = explode(" ", $header);

        if (count($parts) < 3)
            throw new Exception("Wrong header field!");

        return $parts[1];
    }

    /**
     * Makes Rest Response Object
     * @param $response string raw response form web-api
     * @throws Exception
     */
    private function retrieveRestResponseInfo(string $response)
    {
        if (!isset($response))
            throw new Exception("Wrong input");

        $parts = explode("\r\n", $response);

        $this->responseBody = end($parts);

        $this->restResponse->setBody($this->responseBody)
            ->setHttpCode($this->retrieveCode($parts[0]))
            ->setTime($this->startTime, $this->endTime);
    }

    /**
     * Constructs header fields.
     * @return string
     */
    private function makeInitialHeaderFields() : string
    {
        $headerFields = "{$this->method} " . $this->path . " HTTP/1.1\r\n";
        $headerFields .= "Host: ". $this->host . "\r\n";
        $headerFields .= "Content-Type: {$this->contentType}\r\n";
        $headerFields .= "Content-Length: " . strlen($this->body)."\r\n";
        $headerFields .= "Connection: Close\r\n\r\n";
        $headerFields .= $this->body;

        return $headerFields;
    }

    /**
     * HttpSocketCall constructor.
     * @param $file
     * @throws Exception
     */
    public function __construct($file) {

        if (!isset($file))
            throw new Exception("Bad parameter in HttpSocketCall constructor!");

        $this->file = $file;
        $this->restResponse = new RestResponse();
    }

    /**
     * Flag, to tell the class if it needs
     * to wait for response form the server,
     * or continue execution without waiting
     * for response.
     * @param bool $isWaitingForResponse
     */
    public function isWaitingForResponse(bool $isWaitingForResponse)
    {
        $this->isWaitingForResponse = $isWaitingForResponse;
    }

    /**
     * Sets URL.
     * @override
     * @param mixed $url
     * @throws Exception
     */
    public function setUrl(string $url)
    {
        if (!isset($url))
            throw new Exception("Bad input in setUrl!");

        $this->url = $url;
        $parts = parse_url($url);

        if (count($parts) < 2)
            throw new Exception("Bad URL!");

        $this->host = $parts['host'];
        $this->path = $parts['path'];
    }

    /**
     * Sends a request to server.
     * @throws Exception
     */
    public function send()
    {
        $this->startTime = $this->takeTime();

        $socket = new SocketCall($this->file);
        $socket = $socket->setHost($this->host)
            ->setPort(80)
            ->setTimeout(30)
            ->isWaitingForResponse($this->isWaitingForResponse)
            ->setData($this->makeInitialHeaderFields());

        $response = $socket->send();

        $this->endTime = $this->takeTime();

        if (!is_null($response))
        {
            $this->responseRaw = $response;
            $this->retrieveRestResponseInfo($response);
        }
    }

    /**
     * Represents the whole response.
     * That includes the request line
     * and the header lines.
     * @return string representing
     * the whole response.
     */
    public function getResponseRaw() : string
    {
        return $this->responseRaw;
    }

    /**
     * Gives back the response
     * form the server as JSON object.
     * @throws Exception
     */
    public function getResponseAsJson()
    {
        return $this->file->jsonDecode($this->restResponse->getBodyRaw());
    }

    /**
     * Gives back the response
     * form the server as an array.
     * @return string
     */
    public function getResponseAsArray()
    {
        // TODO: Implement getResponseAsArray() method.
    }

    /**
     * Gives back the response
     * form the server as a packed
     * Rest Response object, that holds
     * some information about the request.
     * @return RestResponse object
     */
    public function getResponseWithInfo() : RestResponse
    {
       return $this->restResponse;
    }

    /**
     * Gives back the response
     * form the server as string.
     * @return string
     */
    public function getResponseAsString()
    {
        return $this->responseBody;
    }
}
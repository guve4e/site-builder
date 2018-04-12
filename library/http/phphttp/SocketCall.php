<?php

/**
 * Raw socket HTTP Request
 * @version     2.1.0
 * @category    class
 * @license     GNU Public License <http://www.gnu.org/licenses/gpl-3.0.txt>
 */

class SocketCall extends AHttpRequest
{
    /**
     * @var int
     * Default port
     */
    private $port = 80;

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
     * @var bool
     * If set to true, send() method
     * will wait for server response.
     * If not, it will send the request
     * and continue without waiting for
     * response.
     */
    private $isWaitingForResponse = true;

    /**
     * Socket Timeout
     * @var int
     */
    private $socketTimeout = 30;

    /**
     * @var object
     * Provides file system
     * functionality
     */
    private $file;

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
     * SocketCall constructor.
     * @param $file
     * @throws Exception
     */
    public function __construct($file) {

        if (!isset($file))
            throw new Exception("Bad parameter in SocketCall constructor!");

        $this->file = $file;
        $this->restResponse = new RestResponse();
    }

    /**
     * Sets valid port.
     * @param int $port
     */
    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * Static constructor / factory
     * @throws Exception
     */
    public static function create(File $file = null) : SocketCall
    {
        $instance = new self($file);
        return $instance;
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
        $this->host = $parts['host'];
        $this->path = $parts['path'];
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
     * Sends a request to server.
     * @throws Exception
     */
    public function send()
    {
        $this->startTime = $this->takeTime();

        $fp = $this->file->socket($this->host, $this->port, $this->socketTimeout);

        $headerFields = $this->makeInitialHeaderFields();

        $this->file->write($fp, $headerFields);

        if ($this->isWaitingForResponse)
        {
            $response = "";

            // Wait for the response
            // and collect it.
            while (!$this->file->endOfFile($fp))
                $response .= $this->file->getLine($fp, 4096);

            $this->endTime = $this->takeTime();

            $this->file->close($fp);

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
     */
    public function getResponseAsJson()
    {
        return $this->file->jsonEncode($this->restResponse->getBody());
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
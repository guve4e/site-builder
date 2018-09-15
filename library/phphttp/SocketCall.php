<?php

/**
 * Raw socket call
 */
class SocketCall
{
    /**
     * @var int
     * Default port
     */
    private $port;

    /**
     * @var string
     * Host extracted from url.
     * Ex: http://house-net.ddns.net/secure/index.html
     * host =  http://house-net.ddns.net
     */
    private $host;

    /**
     * @var object
     * The data to be sent.
     */
    private $data;

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
     * Validates properties before
     * establishing connection.
     * @throws Exception
     */
    private function validateProperties()
    {
        if (!isset($this->host) || !isset($this->port) || !isset($this->socketTimeout) || !isset($this->data))
            throw new Exception("Socket parameters are not correctly set!");
    }

    /**
     * HttpSocketCall constructor.
     * @param FileManager $file
     * @throws Exception
     */
    public function __construct(FileManager $file) {

        if (!isset($file))
            throw new Exception("Bad parameter in HttpSocketCall constructor!");

        $this->file = $file;
    }

    /**
     * Host setter.
     * @param int $port
     * @return SocketCall
     */
    public function setHost(string $host): SocketCall
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Port number setter.
     * @param int $port
     * @return SocketCall
     */
    public function setPort(int $port): SocketCall
    {
        $this->port = $port;
        return $this;
    }

    /**
     * Timeout setter.
     * @param int $timeout
     * @return SocketCall
     */
    public function setTimeout(int $timeout): SocketCall
    {
        $this->socketTimeout = $timeout;
        return $this;
    }

    /**
     * Data setter.
     * @param $data
     * @return SocketCall
     */
    public function setData($data): SocketCall
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Flag, to tell the class if it needs
     * to wait for response form the server,
     * or continue execution without waiting
     * for response.
     * @param bool $isWaitingForResponse
     * @return SocketCall
     */
    public function isWaitingForResponse(bool $isWaitingForResponse): SocketCall
    {
        $this->isWaitingForResponse = $isWaitingForResponse;
        return $this;
    }

    /**
     * Sends a request to server.
     * @throws Exception
     */
    public function send()
    {
        // validate
        $this->validateProperties();

        // open socket
        $fp = $this->file->socket($this->host, $this->port, $this->socketTimeout);

        // write valid data
        $this->file->write($fp, $this->data);

        if ($this->isWaitingForResponse)
        {
            $response = "";

            // Wait for the response
            // and collect it.
            while (!$this->file->endOfFile($fp))
                $response .= $this->file->getLine($fp, 4096);

            $this->file->close($fp);
            return $response;
        }

        return null;
    }
}
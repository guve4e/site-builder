<?php

/**
 * Simple Data Class
 * to represent a Rest Response.
 * @version     2.1.0
 * @category    class
 * @license     GNU Public License <http://www.gnu.org/licenses/gpl-3.0.txt>
 */

class RestResponse
{
    /**
     * The code that the server
     * sent.
     *
     * @var integer
     */
    private $http_code;

    /**
     * The actual data
     *
     * @var mixed
     */
    private $body;

    /**
     * The total time taken
     * to complete the request
     * @var
     */
    private $timeSpent;

    /**
     * RestResponse constructor.
     */
    public function __construct() {

    }

    /**
     * Sets up the http code returned
     * from the server.
     * @param mixed $http_code
     * @return RestResponse
     * @throws Exception
     */
    public function setHttpCode(string $http_code) : RestResponse
    {
        if (!isset($http_code))
            throw new Exception("Null Code!");

        $this->http_code = $http_code;
        return $this;
    }

    /**
     * Calculates the time needed
     * for the request.
     * @param $startTime
     * @param $endTime
     * @return RestResponse
     * @throws Exception
     */
    public function setTime(float $startTime, float $endTime ) : RestResponse
    {
        if (!isset($startTime) || !isset($endTime))
            throw new Exception("Null Time!");

        $this->timeSpent = $endTime - $startTime;
        return $this;
    }

    /**
     * Packs into the object a
     * body filed, containing the
     * body of the request.
     * @param string $body
     * @return RestResponse
     * @throws Exception
     */
    public function setBody(string $body) : RestResponse
    {
        if (!isset($body))
            throw new Exception("Null Body!");

        $this->body = $body;
        return $this;
    }

    /**
     * Calculates the success of
     * the request.
     * @return bool
     */
    public function isSuccessful() : bool
    {
        return $this->http_code < 300;
    }

    /**
     * Gives the body as a string.
     * @return string
     * Raw string.
     * Note, not json_encoded
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * Retrieves information
     * about the request.
     * @return mixed
     * Info about the call.
     */
    public function getInfo() : array
    {
        return [
            "code" => $this->http_code,
            "time" => $this->timeSpent,
            "success" => $this->isSuccessful()
        ];
    }
}

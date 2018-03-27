<?php

require_once("AHttpRequest.php");
require_once("RestResponse.php");

/**
 * CurlCall is more user friendly wrapper over
 * crul.
 *
 * @version     2.1.0
 * @category    class
 * @license     GNU Public License <http://www.gnu.org/licenses/gpl-3.0.txt>
 */

class CurlCall extends AHttpRequest
{
    /**
     * RestCall constructor.
     */
    function __construct() {
        // check if php_curl is installed
        if (!function_exists('curl_version'))
            throw new Exception("PHP Curl not installed");
    }

    /**
     * Static constructor / factory
     */
    public static function create() : CurlCall
    {
        $instance = new self();
        return $instance;
    }

    /**
     * Makes HTTP Call to specified URL
     *
     * @return string json string / Curl Response
     * @throws Exception
     */
    public function send()
    {
        // preconditions
        if ($this->method == null) throw new Exception("Null Method");
        if ($this->contentType == null) throw new Exception("Null Content Type");
        if ($this->url == null) throw new Exception("Null Url");

        // initialize
        $curl = curl_init($this->url);

        // TRUE to return the transfer as a string
        // of the return value of curl_exec()
        // instead of outputting it out directly.
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // A custom request method to use instead of "GET" or
        // "HEAD" when doing a HTTP request. This is useful
        // for doing "DELETE" or other, more obscure HTTP requests.
        // Valid values are things like "GET", "POST", "CONNECT" and so on;
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);

        // set headers
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);

        if ($this->body)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->body);

        try {
            $this->startTime = $this->takeTime();
            $response = curl_exec($curl);
            $this->endTime = $this->takeTime();

            $info = curl_getinfo($curl);
            $resError =  json_last_error();

            if($resError != 0)
                throw new Exception("Exception in curl!");

            $this->retrieveRestResponseInfo($response, $info);

        } catch (Exception $e) {
            $e->getTrace();
        } finally {
            curl_close($curl);
        }
    }

    /**
     * Makes Rest Response Object
     * @param $response
     * @param $info
     * @throws Exception
     */
    private function retrieveRestResponseInfo($response, $info)
    {
        $this->responseBody = $response;

        $res = new RestResponse();
        $res->setHttpCode($info['http_code'])
            ->setTime($this->startTime, $this->endTime)
            ->setBody($response);

        $this->restResponse = $res;
    }

    /**
     * Returns the body of the
     * response as JSON object.
     * @return \PHPUnit\Util\Json
     */
    public function getResponseAsJson()
    {
        return json_decode($this->responseBody);
    }

    /**
     * Returns the body of the response
     * wrapped with RestResponse class,
     * providing info about the request.
     * @return RestResponse
     */
    public function getResponseWithInfo()
    {
        return $this->restResponse;
    }

    /**
     * Returns the body of the
     * response as string.
     * @return string
     */
    public function getResponseAsString()
    {
        return $this->responseBody;
    }
}
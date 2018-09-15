<?php
require_once("IHttpRequest.php");
/**
 * HttpRequest class
 *
 * @version     2.1.0
 * @category    class
 * @license     GNU Public License <http://www.gnu.org/licenses/gpl-3.0.txt>
 */

abstract class AHttpRequest implements IHttpRequest
{
    /**
     * @var string
     * represent URL
     */
    protected $url;

    /**
     * @var string
     * HTTP Method
     * (GET, POST, etc)
     */
    protected $method;

    /**
     * @var
     * MIME type
     * Header field content-type
     */
    protected $contentType = "application/x-www-form-urlencoded";

    /**
     * @var
     * Header fields
     */
    protected $headers;

    /**
     * @var mixed
     * Data to be sent
     */
    protected $body;
    
    /**
     * @var boolean
     * Debugging flag.
     */
    protected $debug = true;

    /**
     * @var int
     * Start Time
     * Time taken before the call.
     */
    protected $startTime;

    /**
     * @var int
     * Start Time
     * Time taken after the call.
     */
    protected $endTime;

    /**
     * @var string
     * Represents the whole response.
     * That includes the request line
     * and the header lines.
     */
    protected $responseRaw;

    /**
     * @var string
     * Represents the body of the response.
     */
    protected $responseBody;

    /**
     * @var null|RestResponse object.
     * This object contains little
     * info about the request.
     */
    protected $restResponse = null;

    /**
     * Take time in microsecond
     * @return float
     */
    protected function takeTime() : float
    {
        return microtime(true);
    }

    /**
     * Sets Method.
     * @precondition Valid Method Name
     * @param mixed $method
     * @throws Exception
     */
    public function setMethod(string $method)
    {
    	// preconditions
        if ($method == null) throw new Exception("Null Method");

        $this->method = $method;
        if ($method == "GET") $this->data_send = false;
        else $this->data_send = true;
    }

    /**
     * Sets the content type.
     * @precondition Valid Content Type
     * @param mixed $contentType
     * @throws Exception
     */
    public function setContentType(string $contentType)
    {
    	  // preconditions
        if ($contentType == null) throw new Exception("Null ContentType");

        $this->contentType = $contentType;
        $this->headers [] = 'Content-Type: ' . $contentType;
    }

    /**
     * Sets headers.
     * @param mixed $headers
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->headers[] = $key . ': ' . $value;
        }
    }

    /**
     * Adds key, value pair as a
     * header field.
     * @param string $fieldName
     * @param string $fieldValue
     * @return mixed
     */
    public function addHeader(string $fieldName, string $fieldValue)
    {
        $this->headers[] = $fieldName . ': ' . $fieldValue;
    }

    /**
     * Sets URL.
     * @param mixed $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * Encodes data in json format.
     * @param mixed $jsonData
     * @throws Exception
     */
    public function addBody(array $jsonData)
    {
        // preconditions
        if ($jsonData == null) throw new Exception("Null Json Data");
        
          $this->body = json_encode($jsonData);
    }

    /**
     * Debug.
     * @param $debug
     * @throws Exception
     */
    public function setDebug(bool $debug)
    {
        if ($debug == true) $this->debug = $debug;
        else if ($debug == false) $this->debug = $debug;
        else throw new Exception("Debug Value must be boolean!");
    }

    abstract public function send();
}


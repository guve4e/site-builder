<?php

/**
 * Data class representing info
 * extracted from json file, describing
 * a post request and what happens after
 * the request is done.
 */
final class ServiceConfig
{
    /**
     * @var string
     * Representing a path
     * to the json configuration
     * file that contains information
     * about the POST request
     */
    private $path;

    /**
     * @var mixed
     * All the information
     * extracted from the
     * json file is stored
     * in this json object
     */
    private $json;

    /**
     * @var string
     * Represents request
     * method
     * Ex: POST, PUT, DELETE
     */
    private $method;

    /**
     * @var array
     * Representing the headers
     * used for the request
     */
    private $headers = array();

    /**
     * @var string
     * Representing the
     * content type
     * used for the request
     */
    private $contentType;

    /**
     * @var Boolean
     * Does the service
     * needs to navigate to
     * next page or not
     */
    private $navigable;

    /*
     * @var string
     * Representing
     * request parameter
     */
    private $parameter = "";

    /**
     * @var string
     * Representing the path
     * to a view after successful
     * request
     */
    private $pathSuccess;

    /**
     * @var string
     * Representing the path
     * to a view after unsuccessful
     * request
     */
    private $pathFail;

    /**
     * @var string
     * The name of the service.
     */
    private $serviceName;

    /**
     * @var object
     * Provides file system
     * functionality
     */
    private $file;

    /**
     * Construct the right path
     * to the json config file
     *
     * @param $serviceForm: object of type ServiceForm
     * @throws Exception
     */
    private function constructPath(ServiceForm $serviceForm)
    {
        $this->path =  VIEW_PATH . "/" . $serviceForm->getView() . "/" . $serviceForm->getServiceName() .
            "/" . $serviceForm->getSubView() . ".json";
        // check if file exist
        if(!$this->file->fileExists($this->path))
            throw new Exception("File " . $this->path . "json does not exist!");
    }

    /**
     * Checks if validateParameter is
     * valid object
     *
     * @param $serviceForm
     * @throws Exception
     */
    private function validateParameter($serviceForm)
    {

        $this->view = $serviceForm->getView();
        $this->subView = $serviceForm->getSubView();
        $this->serviceName = $serviceForm->getServiceName();
        $this->fields = $serviceForm->getFields();
    }

    /**
     * Loads the json config file
     * @throws Exception
     */
    private function loadJson()
    {
        // get info from json
        $string = $this->file->loadFileContent($this->path);

        // the second parameter returns an array
        // instead of object
        $this->json = $this->file->jsonDecode($string, true);
    }

    /**
     * Parses the Json config file.
     * Assign attributes.
     */
    private function parseJson()
    {
        $this->method =  $this->json['method'];
        $this->headers = $this->json['headers'];
        $this->contentType = $this->json['content_type'];
        $this->navigable = $this->json['navigate_next'];
        $this->pathSuccess = $this->json['path_success'];
        $this->pathFail = $this->json['path_fail'];
    }

    /**
     * Checks if this is the right
     * json file
     * @throws Exception
     */
    private function validateJson()
    {
        if( !isset($this->json['method']) || !isset($this->json['headers'])
            || !isset($this->json['content_type'])
            || !isset($this->json['navigate_next']) || !isset($this->json['path_success']) || !isset($this->json['path_fail']))
                throw new Exception("Bad Service Configuration Json File");
    }

    /**
     * Constructs parameter
     * @param $serviceForm
     */
    private function constructParameter($serviceForm)
    {
        if (!is_null($serviceForm->getQueryStringParamName()) || !is_null($serviceForm->getQueryStringParamValue()))
            $this->parameter = "&" . $serviceForm->getQueryStringParamName() . "=" . $serviceForm->getQueryStringParamValue();
    }

    /**
     * @param mixed $path_success
     */
    private function constructPathSuccess($path_success)
    {
        $success = $path_success;
        $this->pathSuccess = $success . $this->parameter;
    }

    /**
     * @param mixed $path_fail
     */
    private function constructPathFail($path_fail)
    {
        $fail = $path_fail;
        $this->pathFail = $fail . $this->parameter;
    }

    /**
     * ServiceConfig constructor.
     * @param $serviceForm
     * @throws Exception
     */
    public function __construct(File $file, ServiceForm $serviceForm)
    {
        if(!isset($file) || !isset($serviceForm))
            throw new Exception("Bad parameters in ServiceConfig Constructor!");

        $this->file = $file;

        $this->validateParameter($serviceForm);
        $this->constructParameter($serviceForm);
        $this->constructPath($serviceForm);
        $this->loadJson();
        $this->validateJson();
        $this->parseJson();
        $this->constructPathSuccess($this->pathSuccess);
        $this->constructPathFail($this->pathFail);
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return mixed
     */
    public function getPathSuccess()
    {
        return $this->pathSuccess;
    }

    /**
     * @return mixed
     */
    public function getPathFail()
    {
        return $this->pathFail;
    }

    /**
     * @return mixed
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return bool
     */
    public function isNavigable()
    {
        return $this->navigable;
    }
}
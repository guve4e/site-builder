<?php

class FormExtractor
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var mixed
     */
    private $entity;

    /**
     * @var mixed
     */
    private $verb;

    /**
     * @var mixed
     */
    private $parameter;

    /**
     * @var bool
     */
    private $navigate;

    /**
     * @var mixed
     */
    private $pathSuccess;

    /**
     * @var mixed
     */
    private $pathFail;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var array|mixed
     */
    private $pathSuccessParams = [];

    /**
     * @var array|mixed
     */
    private $pathFailParams = [];

    /**
     * @var
     */
    private $instance;

    /**
     * Validates if the super-globals $_GET and $_POST
     * contain the right information.
     *
     * @param $get: representing the $_GET super-global
     * @param $post: representing the $_POST super-global
     * @throws Exception
     */
    private function validateAttributes(FileManager $file, array $get, array $post)
    {
        if(!isset($file) || !isset($get['entity']) || !isset($get["verb"]) || !isset($get['parameter']) ||
            !isset($get['navigate']) || !isset($get['path_success']) || !isset($get['path_fail']) || !isset($post))
            throw new Exception("Invalid Arguments in FormExtractor.");

        if(!is_array($post))
            throw new Exception("Fields attribute is not of type array.");
    }

    /**
     * @param array $parameters
     * @return array
     * @throws Exception
     */
    private function retrieveParameters(array $parameters): array
    {
        $parametersArray = [];

        foreach($parameters as $parameter)
        {
            $params = explode(":", $parameter);

            if (count($params) != 2)
                throw new Exception("Bad path parameters!");

            $parametersArray[$params[0]] = $params[1];
        }

        return $parametersArray;
    }

    /**
     * @param string $parameters
     * @return string
     * @throws Exception
     */
    private function constructPath(string $parameters): string
    {
        $parametersArray = $this->retrieveParameters(explode("/", $parameters));

        $paramSting = "";

        foreach($parametersArray as $key => $value)
            $paramSting .= "&{$key}={$value}";

        return $paramSting;
    }

    /**
     * @throws Exception
     */
    private function constructPathFail()
    {
        $this->pathFail .= $this->constructPath($this->pathFailParams);
    }

    /**
     * @throws Exception
     */
    private function constructPathSuccess()
    {
        $this->pathSuccess .= $this->constructPath($this->pathSuccessParams);
    }

    /**
     * @param $success
     * @throws Exception
     */
    private function navigate($success)
    {
        if ($success === null)
            throw new Exception("Resource method '{$this->verb}' didn't return success boolean!");

        if($this->navigate)
        {
            // if response is not successful
            if ($success) {
                header("Location: " . "./?page=" . $this->pathSuccess);
            } else { // if response is successful
                // Log it first
                // TODO
                header("Location: " . "./?page=" . $this->pathFail);
            }
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function callResource()
    {
        // if file exists include it
        $class = ucfirst($this->entity);
        $path = DATA_RESOURCE_PATH . "/" . $class . ".php";

        if ($this->fileManager->fileExists($path))
            require_once($path);
        else
            throw new Exception("Resource file {$path} does not exists!");

        // text substitution
        // @example:
        // $test = new Test();
        $this->instance = new $class();

        // get the request method
        $method = $this->verb;

        // invoke method with the right parameter, if provided
        return $this->instance->$method($this->parameter, $this->data);
    }

    /**
     * ServiceForm constructor.
     *
     * @param array $get : representing $_GET
     * @param array $post : representing $_POST
     * @throws Exception
     */
    public function __construct(FileManager $file, array $get, array $post)
    {
        $this->validateAttributes($file, $get, $post);
        $this->fileManager = $file;

        // Now we know that _GET and _POST are proper

        if ( $get['navigate'] == "true" || $get['navigate'] == "1")
            $this->navigate = true;
        else
            $this->navigate = false;

        $this->entity = $get['entity'];
        $this->verb = $get["verb"];
        $this->parameter = $get['parameter'];
        $this->pathSuccess = $get['path_success'];
        $this->pathFail = $get['path_fail'];

        // check if navigation with parameters

        if (isset($get['path_success_params'])) {
            $this->pathSuccessParams = $get['path_success_params'];
            $this->constructPathSuccess();
        }

        if (isset($get['path_fail_params'])) {
            $this->pathFailParams = $get['path_fail_params'];
            $this->constructPathFail();
        }

        $this->data = json_decode(json_encode($post));

        $success = $this->callResource();
        $this->navigate($success);
    }
}
<?php

class FormExtractor
{
    private $entity;
    private $verb;
    private $parameter;
    private $navigate;
    private $path_success;
    private $path_fail;
    private $data;

    private $instance;

    /**
     * Validates if the super-globals $_GET and $_POST
     * contain the right information.
     *
     * @param $get: representing the $_GET super-global
     * @param $post: representing the $_POST super-global
     * @throws Exception
     */
    private function validateAttributes(array $get, array $post)
    {
        if(!isset($get['entity']) || !isset($get["verb"]) || !isset($get['parameter']) ||
            !isset($get['navigate']) || !isset($get['path_success']) || !isset($get['path_fail']) ||
            !isset($post))
            throw new Exception("Invalid Arguments in FormExtractor.");

        if(!is_array($post))
            throw new Exception("Fields attribute is not of type array.");
    }

    /**
     * ServiceForm constructor.
     *
     * @param array $get : representing $_GET
     * @param array $post : representing $_POST
     * @throws Exception
     */
    public function __construct(array $get, array $post)
    {
        $this->validateAttributes($get, $post);

        // Now we know that _GET and _POST are proper

        if ( $get['navigate'] == "true" || $get['navigate'] == "1")
            $this->navigate = true;
        else
            $this->navigate = false;

        $this->entity = $get['entity'];
        $this->verb = $get["verb"];
        $this->parameter = $get['parameter'];
        $this->path_success = $get['path_success'];
        $this->path_fail = $get['path_fail'];

        $this->data = json_decode(json_encode($post));

        $success = $this->callResource();
        $this->navigate($success);
    }

    private function navigate(bool $success)
    {
        if($this->navigate)
        {
            // if response is not successful
            if ($success) {
                header("Location: " . "./?page=" . $this->path_success);
            } else { // if response is successful
                // Log it first
                // TODO
                header("Location: " . "./?page=" . $this->path_fail);
            }
        }
    }

    public function callResource()
    {
        // if file exists include it
        $class = ucfirst($this->entity);
        $path = DATA_RESOURCE_PATH . "/" . $class . ".php";
        require_once($path);

        // text substitution
        // @example:
        // $test = new Test();
        $this->instance = new $class();

        // get the request method
        $method = $this->verb;

        // invoke method with the right parameter, if provided
        return $this->instance->$method($this->parameter, $this->data);
    }
}
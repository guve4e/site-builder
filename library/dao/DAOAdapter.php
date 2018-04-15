<?php
/**
 * Created by PhpStorm.
 * User: guve4
 * Date: 4/15/2018
 * Time: 4:23 PM
 */

class DAOAdapter
{
    /**
     * @var string
     * The name of the dao class.
     * to be invoked
     */
    private $dao;

    /**
     * @var string
     * The method
     */
    private $method;

    /**
     * @var mixed
     * Parameter passed to
     * the method.
     */
    private $parameter;

    /**
     * @var string
     * The path leading to
     * the right dao class
     */
    private $daoPath;

    /**
     * __construct
     *
     * @access public
     * @param $pathInfo  $_SERVER['PATH_INFO']
     * @throws Exception
     */
    public function __construct(Service $service)
    {
        // sanitize path info first
        $this->splitPathInfo($pathInfo);

        // get the controller
        $this->retrieveControllerName();

        // get the method
        $this->method = $this->retrieveMethodType();

        // get the parameter
        $this->retrieveParameter();

        // build
        $this->build();

    }

    /**
     * Retrieves the Method Type
     * used by the client to
     * make a request to the web-api
     *
     * @return string method name
     * @throws
     */
    private function retrieveMethodType()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if ($requestMethod == "GET") return "get";
        else if ($requestMethod == "HEAD") return "head";
        else if ($requestMethod == "POST") return "post";
        else if ($requestMethod == "PUT") return "put";
        else if ($requestMethod == "DELETE") return "delete";
        else if ($requestMethod == "OPTIONS") return "options";
        else if ($requestMethod == "PATCH") return "patch";
        else throw new NoSuchMethodException($requestMethod);
    }

    /**
     * Extracts the controller name form
     * member array pathInfo
     */
    private function retrieveControllerName()
    {
        $this->dao = $this->pathInfo[0];
    }

    /**
     * Extracts the controller parameter form
     * member array pathInfo
     */
    private function retrieveParameter()
    {
        // get the id
        if (isset($this->pathInfo[1])) $this->parameter = $this->pathInfo[1];
    }

    /**
     * It explodes the array given as
     * parameter and validates it.
     * @param $pathInfo: string,
     * @throws ApiException
     */
    private function splitPathInfo($pathInfo)
    {
        // explode and trim
        $this->pathInfo = explode('/', trim($pathInfo,'/'));

        // make sure the $s_path_info no null requests
        if ($this->pathInfo == null) throw new ApiException("PATH_INFO");

        // make sure that request is in the form "/controller/method/id"
        // if $request array has more than 3 elements throw exception
        if (count($this->pathInfo) > 2) throw new ApiException("Wrong Request");
    }

    /**
     * Takes te first char from the
     * controller name and makes it
     * capital, if already not.
     */
    private function sanitizeControllerName()
    {
        // make the first letter of the controller uppercase
        $this->dao = ucfirst($this->dao);
    }

    /**
     * Constructs the controller path form
     * the parameter given and hard wired path to
     * direcotry. Then it checks if the file exists
     * @throws NoSuchControllerException
     */
    private function constructControllerPath()
    {
        // construct controller
        // controllers' folder + controller-folder + controller-file
        // the reason each controller to be in its own folder is that some
        // controllers have database access and logic in the same folder
        $this->daoPath = CONTROLLERS_PATH . "/" . $this->dao  . "/" . $this->dao . '.php';

        // check if controller exists
        if (!file_exists( $this->daoPath)) throw new NoSuchControllerException($this->dao, "ControllerFactory.phpry.php", 143);
    }

    /**
     * Creates an object of the
     * required controller using
     * string substitution
     * (the good stuff that comes
     * with using dynamic languages)
     * @throws ApiException, NoSuchControllerException
     */
    private function build()
    {
        // sanitize controller name first
        $this->sanitizeControllerName();

        //then validate if it exist
        $this->constructControllerPath();

        // if file exists include it
        require_once($this->daoPath);

        // text substitution
        // @example:
        // $test = new Test();
        $this->instance = new $this->dao();

        // authorize the controller
        if (!$this->instance->authorize($this->instance))
        {
            throw new NotAuthorizedException();
        }

        // get the request method
        $method = $this->method;

        // invoke method with the right parameter, if provided
        $this->instance->$method($this->parameter);
    }
}
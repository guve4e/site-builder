<?php
/**
 * Wrapper around ServiceForm and ServiceConfig.
 * It provides a request parameter.
 * If client doesn't specify a request parameter,
 * the user id extracted form $_SESSION will be used as one.
 */

final class Service
{
    /**
     * @var object
     * of type ServiceConfig
     */
    private $serviceConfig;

    /**
     * @var object
     * of type ServiceForm
     */
    private $serviceForm;

    /**
     * @var string
     * session username
     */
    private $sessionName = "crystalpure_user";

    /**
     * @var mixed
     * parameter for rest request
     */
    private $parameter;

    /**
     * Checks of the objects provided are valid
     *
     * @param $serviceConfig: object of type ServiceConfig
     * @param $serviceForm: object of type ServiceForm
     * @throws Exception
     */
    private function validateAttributes($serviceConfig, $serviceForm)
    {

        if(!isset($serviceConfig) || !isset($serviceForm))
            throw new Exception("Parameters Not set in Service");
        if(!isset($_SESSION[$this->sessionName]))
            throw new Exception("SESSION is not set");
    }
    
    /**
     * Retrieves Parameter used
     * to send with rest request.
     * Sets member $parameter.
     * If client does not supplies parameter,
     * user id is used, retrieved from the
     * $_SESSION super-global
     */
    private function retrieveParameter()
    {
        // get the parameter
        $param = $this->serviceForm->getRequestParameter();

        if(!isset($param)) // if not provided
        {
            $user = $_SESSION[$this->sessionName];
            $this->parameter = $user->u_id;
        }
        else
        {
            $this->parameter = $this->serviceForm->getRequestParameter();
        }
    }

    /**
     * Service constructor.
     * @param $serviceConfig : object of type ServiceConfig
     * @param $serviceForm : object of type ServiceForm
     * @throws Exception
     */
    public function __construct($serviceConfig, $serviceForm)
    {
        $this->validateAttributes($serviceConfig, $serviceForm);

        $this->serviceConfig = $serviceConfig;
        $this->serviceForm = $serviceForm;
        $this->retrieveParameter();
    }

    /**
     * @return mixed
     */
    public function getServiceConfig()
    {
        return $this->serviceConfig;
    }

    /**
     * @return mixed
     */
    public function getServiceForm()
    {
        return $this->serviceForm;
    }

    /**
     * @return mixed
     */
    public function getParameter()
    {
        return $this->parameter;
    }
}

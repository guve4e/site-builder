<?php
session_start();

require_once ("../relative-paths.php");
require_once (UTILITY_PATH . '/Logger.php');
require_once (HTTP_PATH . '/PhpHttpAdapter.php');
require_once (LIBRARY_PATH . "/form/ServiceForm.php");
require_once (LIBRARY_PATH . "/form/ServiceConfig.php");
require_once (LIBRARY_PATH . "/form/Service.php");

$info = function () {
    try {
        // gather information
        $serviceForm = new ServiceForm($_GET, $_POST);
        $serviceConfig = new ServiceConfig(new File(), $serviceForm);
        $service = new Service($serviceConfig, $serviceForm);
    } catch (Exception $e) {

        // TODO not really! Why the user should know about this?
        echo $e->getMessage();
    }
    return $service;
};

// make a call
$service = $info();

if (!is_null($service))
{
    $http = new PhpHttpAdapter();
    $http->setWebServiceName($service->getServiceConfig()->getServiceName())
        ->setParameter($service->getParameter())
        ->setMethod($service->getServiceConfig()->getMethod())
        ->setDataToSend($service->getServiceForm()->getFields());

    try {
        $res = $http->send();
    } catch (Exception $e) {
        //TODO What here?
    }

    if($service->getServiceConfig()->isNavigable())
    {
        // if response is not successful

        if ($http->isSuccessful()) {
            header("Location: " .  $service->getServiceConfig()->getPathSuccess());

        } else { // if response is successful

            // Log it first
            // TODO
            header("Location: " . $service->getServiceConfig()->getPathFail());
            exit();
        }
    }
}

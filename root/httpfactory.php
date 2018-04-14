<?php
session_start();
require_once("../relative-paths.php");
require_once (UTILITY_PATH . '/Logger.php');
require_once (HTTP_PATH . '/PhpHttpAdapter.php');
require_once(LIBRARY_PATH . "/services/ServiceForm.php");
require_once(LIBRARY_PATH . "/services/ServiceConfig.php");
require_once(LIBRARY_PATH . "/services/Service.php");

$info = function () {
    try {// gather information
        $serviceForm = new ServiceForm($_GET, $_POST);
        $serviceConfig = new ServiceConfig(new File(), $serviceForm);
        $service = new Service($serviceConfig, $serviceForm);
        return $service;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
};

// make a call
$service = $info();

if (!is_null($service))
{
    $http = new PhpHttpAdapter();
    $http->setServiceName($service->getServiceConfig()->getServiceName())
        ->setParameter($service->getParameter())
        ->setMethod($service->getServiceConfig()->getMethod())
        ->setJsonData($service->getServiceForm()->getFields());

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

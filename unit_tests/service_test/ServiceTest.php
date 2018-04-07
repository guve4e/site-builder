<?php

require_once("../../config.php");
require_once("../UtilityTest.php");
require_once (LIBRARY_PATH . "/service/Service.php");
require_once (LIBRARY_PATH . "/service/ServiceConfig.php");
require_once (LIBRARY_PATH . "/service/ServiceForm.php");

use PHPUnit\Framework\TestCase;

/**
 *
 * @group service-group
 */
class ServiceConfigTest extends TestCase
{
    use UtilityTest;

    private $service;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        // Arrange
        $_GET['view'] = "shoppingcart";
        $_GET['subView'] = "del_product";
        $_GET['from'] = "cart";
        $_POST = ["key" => "value"];


        $serviceForm = new ServiceForm($_GET,$_POST);
        $serviceConfig = new ServiceConfig($serviceForm);
        // make a tmp object and assigned to session
        $tmp = new StdClass;
        $tmp->U_ID = "aji2jdnjwec8i1ji3njiwqefjw0efd";

        $_SESSION["crystalpure_user"] = $tmp;

        $this->service = new Service($serviceConfig, $serviceForm);
    }

    /**
     * Create test subject before test
     */
    protected function setUpWithParameter()
    {
        // Arrange
        $_GET['requestParam'] = 123;
        $serviceForm = new ServiceForm($_GET,$_POST);
        $serviceConfig = new ServiceConfig($serviceForm);
        // make a tmp object and assigned to session
        $tmp = new StdClass;
        $tmp->U_ID = "aji2jdnjwec8i1ji3njiwqefjw0efd";

        $_SESSION["crystalpure_user"] = $tmp;

        $this->service = new Service($serviceConfig, $serviceForm);
    }

    /**
     * Test proper setting of $parameter member when the client didn't
     * provide the parameter
     */
    public function testProperSettingUpWithNoParameterProvided()
    {
        // Act
        $parameter = $this->getProperty($this->service, "parameter");

        // Assert
        $this->assertSame("aji2jdnjwec8i1ji3njiwqefjw0efd", $parameter);
    }

    /**
     * Test proper setting of $parameter member when the client
     * provided the parameter
     */
    public function testProperSettingUpWithParameterProvided()
    {
        // Act
        $this->setUpWithParameter();

        $parameter = $this->getProperty($this->service, "parameter");

        // Assert
        $this->assertSame(123, $parameter);
    }
}

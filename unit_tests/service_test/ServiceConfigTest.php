<?php

require_once("../../config.php");
require_once("../UtilityTest.php");
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

    private $info;

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
        $_GET['paramName'] = "product";
        $_GET['paramValue'] = 2;

        $serviceForm = new ServiceForm($_GET, $_POST);
        $this->info = new ServiceConfig($serviceForm);
    }

    /**
     * @expectedException Exception
     */
    public function testConstructionWhenParamIsNotSetExpectedException()
    {
        $this->info = new ServiceConfig(null, "shoppingcart", "del_product");
    }

    /**
     * Test proper setting of members
     */
    public function testProperSettingUp()
    {
        // Arrange
        $expectedJson = [
                "method" => "DELETE",
                "headers" => [
                        "key" => "value",
                        "key2" => "value2",
                        "key3" => "value3"
                    ],
                "content_type" => "application/json",
                "service" => "someservice",
                "path_success" => "./?page=shoppingcart",
                "path_fail" => "./?page=shoppingcart"
            ];

        $expectedPath = VIEW_PATH . "/shoppingcart/cart/del_product.json";

        //$expectedJson = print_r($expectedJson);

        // Act
        $path = $this->getProperty($this->info,"path");
        $json = $this->getProperty($this->info,"json");

        $method = $this->getProperty($this->info,"method");
        $headers = $this->getProperty($this->info,"headers");
        $content_type = $this->getProperty($this->info,"content_type");
        $path_success  = $this->getProperty($this->info,"path_success");
        $path_fail = $this->getProperty($this->info,"path_fail");

        // Assert
        //$this->assertSame($this->arraysAreSimilar($expectedJson, $json));
        $this->assertSame("DELETE", $method);
        $this->assertSame(["key" => "value", "key2" => "value2", "key3" => "value3" ], $headers);
        $this->assertSame("application/json", $content_type);
        $this->assertSame("./?page=shoppingcart&product=2", $path_success);
        $this->assertSame("./?page=shoppingcart&product=2", $path_fail);
        $this->assertSame($expectedPath, $path);
    }
}

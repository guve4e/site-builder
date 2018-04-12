<?php

require_once("../../config.php");
require_once("../UtilityTest.php");
require_once(LIBRARY_PATH . "/services/ServiceConfig.php");
require_once(LIBRARY_PATH . "/services/ServiceForm.php");
require_once(LIBRARY_PATH . "/services/Service.php");
require_once (UTILITY_PATH . "/File.php");

use PHPUnit\Framework\TestCase;

class ServiceAndServiceConfigTest extends TestCase
{
    private $mockFile;

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

        $expectedJson = [
            "method" => "DELETE",
            "headers" => [
                "key" => "value",
                "key2" => "value2",
                "key3" => "value3"
            ],
            "content_type" => "application/json",
            "services" => "someservice",
            "navigate_next" => true,
            "path_success" => "./?page=shoppingcart",
            "path_fail" => "./?page=home"
        ];

        // Set up Session
        $tmp = new StdClass;
        $tmp->u_id = "aji2jdnjwec8i1ji3njiwqefjw0efd";
        $_SESSION["some_website_user"] = $tmp;

        // Create a stub for the JsonLoader class
        $this->mockFile = $this->getMockBuilder(File::class)
            ->setMethods(array('fileExists', 'jsonDecode', 'loadFileContent'))
            ->getMock();

        $this->mockFile->method('fileExists')
            ->willReturn(true);

        $this->mockFile->method('loadFileContent')
            ->willReturn("{ 'key'=>'value' }");

        $this->mockFile->method('jsonDecode')
            ->willReturn($expectedJson);
    }

    public function testProperSettingUp()
    {
        // Arrange

        try {
            $serviceForm = new ServiceForm($_GET, $_POST);
            $info = new ServiceConfig($this->mockFile, $serviceForm);
        } catch (Exception $e) {
        }

        // Assert
        $this->assertEquals(true, $info->isNavigable());
        $this->assertEquals("cart", $info->getServiceName());
        $this->assertEquals("application/json", $info->getContentType());
        $this->assertEquals(["key"=>"value", "key2"=>"value2", "key3"=>"value3",], $info->getHeaders());
        $this->assertEquals("DELETE", $info->getMethod());

        //TODO why add product=2 to path success and fail?
        $this->assertEquals("./?page=home&product=2", $info->getPathFail());
        $this->assertEquals("./?page=shoppingcart&product=2", $info->getPathSuccess());
    }

    public function testWithNoParameter()
    {
        $service = null;
        // Act
        try {
            $serviceForm = new ServiceForm($_GET,$_POST);
            $serviceConfig = new ServiceConfig($this->mockFile, $serviceForm);
            $service = new Service($serviceConfig, $serviceForm);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertEquals("aji2jdnjwec8i1ji3njiwqefjw0efd", $service->getParameter());
        $this->assertEquals($serviceConfig, $service->getServiceConfig());
        $this->assertEquals($serviceForm, $service->getServiceForm());
    }

    public function testWithParameter()
    {
        // Arrange
        $_GET['requestParam'] = 123;

        // Act
        try {
            $serviceForm = new ServiceForm($_GET,$_POST);
            $serviceConfig = new ServiceConfig($this->mockFile, $serviceForm);
            $service = new Service($serviceConfig, $serviceForm);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertEquals(123, $service->getParameter());
        $this->assertEquals($serviceConfig, $service->getServiceConfig());
        $this->assertEquals($serviceForm, $service->getServiceForm());
    }

    public function tearDown()
    {
        $_GET = array();
        $_POST = array();
        $_SESSION = array();
    }
}

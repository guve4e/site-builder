<?php


require_once("../../config.php");
require_once("../UtilityTest.php");
require_once (LIBRARY_PATH . "/service/ServiceForm.php");

use PHPUnit\Framework\TestCase;

/**
 *
 * @group service-group
 */
class ServiceFormTest extends TestCase
{
    use UtilityTest;

    private $info;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        // Arrange
        $_GET['view'] = "some_view";
        $_GET['subView'] = "some_subview";
        $_GET['from'] = "testservice";
        $_GET['paramName'] = "product";
        $_GET['paramValue'] = 2;



        $_POST = ["key" => "value"];

        $this->info = new ServiceForm($_GET, $_POST);
    }

    /**
     * Create test subject for an object
     * where the $paramName and $paramValue
     * attributes are not set
     */
    protected function setUpForNullParamValues()
    {
        // Arrange

        // Clear the $_GET and $_POST superglobals

        $_GET = array();
        $_POST = array();
        unset($_GET);

        $_GET['view'] = "some_view";
        $_GET['subView'] = "some_subview";
        $_GET['from'] = "testservice";

        $_POST = ["key" => "value"];
    }

    /**
     * @expectedException Exception
     */
    public function testConstructionWhenParamIsNotSetExpectedException()
    {
        $this->info = new ServiceForm("view", null, null, ["key" => "value"]);
    }

    /**
     * @expectedException Exception
     */
    public function testConstructionWhenParamIsNotArrayExpectedException()
    {
        $this->info = new ServiceForm("view", "sub_view", null,"string");
    }

    /**
     * Test proper setting of members
     */
    public function testProperSettingUp()
    {
        // Act
        $view = $this->getProperty($this->info,"view");
        $subView = $this->getProperty($this->info,"subView");
        $service = $this->getProperty($this->info,"serviceName");
        $fields = $this->getProperty($this->info,"fields");
        $paramName = $this->getProperty($this->info,"queryStringParamName");
        $paramValue = $this->getProperty($this->info,"queryStringParamValue");

        // Assert
        $this->assertSame("some_view", $view);
        $this->assertSame("some_subview", $subView);
        $this->assertSame("testservice", $service);
        $this->assertSame(["key" => "value"], $fields);
        $this->assertSame("product", $paramName);
        $this->assertSame(2, $paramValue);
    }

    /**
     * Test proper setting of members
     */
    public function testProperSettingUpWhenParamAttributeIsNull()
    {
        // Arrange
        // Clear the superglobals first
        $this->setUpForNullParamValues();

        $info = new ServiceForm($_GET, $_POST);

        // Act
        $view = $this->getProperty($info,"view");
        $subView = $this->getProperty($info,"subView");
        $service = $this->getProperty($info,"serviceName");
        $fields = $this->getProperty($info,"fields");
        $paramName = $this->getProperty($info,"queryStringParamName");
        $paramValue = $this->getProperty($info,"queryStringParamValue");

        // Assert
        $this->assertSame("some_view", $view);
        $this->assertSame("some_subview", $subView);
        $this->assertSame("testservice", $service);
        $this->assertSame(["key" => "value"], $fields);
        $this->assertSame(null, $paramName);
        $this->assertSame(null, $paramValue);
    }
}

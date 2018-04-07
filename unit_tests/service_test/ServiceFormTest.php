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

    protected function setUp()
    {
        // Arrange
        $_GET['view'] = "some_view";
        $_GET['subView'] = "some_subview";
        $_GET['from'] = "testservice";
        $_GET['paramName'] = "product";
        $_GET['paramValue'] = 2;

        $_POST = ["key" => "value"];
    }

    public function testProperConstruction()
    {
        try {
            $this->info = new ServiceForm($_GET, $_POST);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Assert
        $this->assertEquals("some_view", $this->info->getView());
        $this->assertEquals(["key" => "value"], $this->info->getFields());
        $this->assertEquals("product", $this->info->getQueryStringParamName());
        $this->assertEquals(2, $this->info->getQueryStringParamValue());
        $this->assertEquals("testservice", $this->info->getServiceName());
        $this->assertEquals("some_subview", $this->info->getSubView());
    }
}

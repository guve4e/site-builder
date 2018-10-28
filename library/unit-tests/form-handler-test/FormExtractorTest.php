<?php

use PHPUnit\Framework\TestCase;
require_once ("../../../relative-paths.php");
require_once (LIBRARY_PATH . "/form/FormExtractor.php");
require_once (UTILITY_PATH . "/FileManager.php");

class FormExtractorTest extends TestCase
{
    private $mockFile;

    protected function setUp()
    {
        // Arrange
        $_GET['entity'] = "someResource";
        $_GET['verb'] = "update";
        $_GET['parameter'] = 1001;
        $_GET['navigate'] = true;
        $_GET['path_success'] = "home";
        $_GET['path_fail'] = "home";

        $_POST = [
            "product_count" => 3,
            "products" => [
                [
                    "name" => "product1",
                    "price" => 12.4
                ],
                [
                    "name" => "product2",
                    "price" => 4.56
                ],
                [
                    "name" => "product3",
                    "price" => 100.4
                ]
            ]
        ];

        $this->mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists'))
            ->getMock();

        $this->mockFile->method('fileExists')
            ->willReturn(true);
    }

    /**
     * @expectedException Exception
     */
    public function testFormExtractorMustThrowExceptionWhenEntityDoestExist()
    {
        new FormExtractor(new FileManager, $_GET, $_POST, $_FILES);
    }

    /**
     * @throws Exception
     * @runInSeparateProcess
     */
    public function testFormExtractorWithPathSuccessParameters()
    {
        // Arrange
        $_GET['path_success_params'] = 'product:1/key:value';
        $_GET['entity'] = "TestDataResource";
        $expectedHeaders = "Location: ./?page=home&product=1&key=value";

        // Act
        new FormExtractor(new FileManager, $_GET, $_POST, $_FILES);

        // Lets look if we have the right header
        // shift, because we are suppose to have an array with one element arr[0]
        $headers = xdebug_get_headers();
        $actualHeaders = array_shift($headers);

        // Assert
        $this->assertEquals($expectedHeaders, $actualHeaders);
    }

    /**
     * @throws Exception
     * @runInSeparateProcess
     */
    public function testFormExtractorWithPathFailParameters()
    {
        // Arrange
        $_GET['path_fail_params'] = 'product:1/key:value';
        $_GET['entity'] = "TestDataResource";
        $_GET['verb'] = "delete";
        $expectedHeaders = "Location: ./?page=home&product=1&key=value";

        // Act
        new FormExtractor(new FileManager, $_GET, $_POST, $_FILES);

        // Lets look if we have the right header
        // shift, because we are suppose to have an array with one element arr[0]
        $headers = xdebug_get_headers();
        $actualHeaders = array_shift($headers);

        // Assert
        $this->assertEquals($expectedHeaders, $actualHeaders);
    }
}



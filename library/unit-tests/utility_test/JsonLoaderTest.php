<?php

require_once("../../relative-paths.php");
require_once(UTILITY_PATH . "/JsonLoader.php");
require_once(CONFIGURATION_PATH . "/SiteConfigurationLoader.php");
require_once(CONFIGURATION_PATH . "/TemplateConfigurationLoader.php");
require_once(CONFIGURATION_PATH . "/ViewConfigurationLoader.php");
require_once(CONFIGURATION_PATH . "/MenuConfigurationLoader.php");

use PHPUnit\Framework\TestCase;

class JsonLoaderTest extends TestCase
{
    protected $mockFile;

    protected function setUp()
    {
        // Create a stub for the JsonLoader class
        $this->mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'jsonDecode', 'loadFileContent'))
            ->getMock();

        $this->mockFile->method('fileExists')
            ->willReturn(true);

        $this->mockFile->method('loadFileContent')
            ->willReturn("{ 'key'=>'value' }");

        $this->mockFile->method('jsonDecode')
            ->willReturn([ 'key'=>'value' ]);
    }

    public function testLoadJsonWhenFileExists()
    {
        try {
            // Act
            $jsonLoader = new JsonLoader($this->mockFile, "path/to/file");

            // Assert
            $actualData = $jsonLoader->getData();

        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertEquals([ 'key'=>'value' ], $actualData);
    }

    public function testLoadJsonWhenGetDataAsJson()
    {
        $expectedObject = json_decode(json_encode([ 'key'=>'value' ]));

        try {
            // Act
            $jsonLoader = new JsonLoader($this->mockFile, "path/to/file");
            $actualData = $jsonLoader->getDataAsJson();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->assertEquals($expectedObject, $actualData);
    }

    /**
     * @expectedException  Exception
     */
    public function testJsonLoaderWhenFileIsEmptyMustThrowException()
    {
        // Arrange
        // We want to simulate an empty config file
        $mockFile = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'loadFileContent'))
            ->getMock();

        $mockFile->method('fileExists')
            ->willReturn(true);

        $mockFile->method('loadFileContent')
            ->willReturn("");

        // Act
        new JsonLoader($mockFile, "path/to/file");
    }

    /**
     * @expectedException  Exception
     */
    public function testLoadJsonMustThrowExceptionWhenFileDoesNotExists()
    {
        new JsonLoader(new FileManager(), "path/to/file");
    }
}

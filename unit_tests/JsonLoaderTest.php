<?php
/**
 * Tests CookieSetter Class.
 */

require_once("../config.php");
require_once("../library/build_page/json/JsonLoader.php");
require_once("../library/build_page/json/SiteConfigurationLoaderLoader.php");
require_once("../library/build_page/json/ViewConfigurationLoaderLoader.php");
require_once("../library/build_page/json/MenuConfigurationLoaderLoader.php");
require_once ("UtilityTest.php");

use PHPUnit\Framework\TestCase;

class JsonLoaderTest extends TestCase
{
    use UtilityTest;

    protected $mockFile;

    /**
     * Create test subject before test
     */
    protected function setUp() {
        // Create a stub for the JsonLoader class
        $this->mockFile = $this->getMockBuilder(File::class)
            ->setMethods(array('fileExists', 'jsonDecode', 'loadFileContent'))
            ->getMock();

        $this->mockFile->method('fileExists')
            ->willReturn(true);

        $this->mockFile->method('loadFileContent')
            ->willReturn("{ 'key'=>'value' }");

        $this->mockFile->method('jsonDecode')
            ->willReturn([ 'key'=>'value' ]);
    }

    public function testLoadJsonWhenFileExists() {
        try {

            // Act
            $jsonLoader = new JsonLoader($this->mockFile, "path/to/file");

            // Assert
            $actualData = $jsonLoader->getData();

            $this->assertEquals([ 'key'=>'value' ], $actualData);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function testSiteConfigurationLoader() {
        try {
            // Act
            $jsonLoader = new SiteConfigurationLoader($this->mockFile);

            // Assert
            $actualData = $jsonLoader->getData();

            $this->assertEquals([ 'key'=>'value' ], $actualData);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function testViewConfigurationLoader() {
        try {
            // Act
            $jsonLoader = new ViewConfigurationLoader($this->mockFile, "home");

            // Assert
            $actualData = $jsonLoader->getData();

            $this->assertEquals([ 'key'=>'value' ], $actualData);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function testMenuConfigurationLoader() {
        try {
            // Act
            $jsonLoader = new MenuConfigurationLoader($this->mockFile);

            // Assert
            $actualData = $jsonLoader->getData();

            $this->assertEquals([ 'key'=>'value' ], $actualData);
        } catch (Exception $e) {
            echo $e;
        }
    }

    /**
     * @expectedException  Exception
     */
    public function testLoadJsonMustThrowExceptionIfFileDoesNotExists() {
        new JsonLoader(new File(), "path/to/file");
    }

    protected function tearDown()
    {

    }
}

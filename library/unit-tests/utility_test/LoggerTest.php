<?php

require_once("../../relative-paths.php");
require_once ("../UtilityTest.php");
require_once (UTILITY_PATH . "/Logger.php");
require_once(UTILITY_PATH . "/FileManager.php");

use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    protected $errorsPath = LOG_PATH . "/Errors.txt";
    protected $warningsPath = LOG_PATH . "/Warnings.txt";
    protected $debugPath = LOG_PATH . "/Debug.txt";
    protected $messagesPath = LOG_PATH . "/Messages.txt";

    public function testLoadJsonWhenFileExists()
    {
        // Arrange
        $msg = "Blah Blah Blah";

        try {
            // Act
            $logger = new Logger(new FileManager());
            $logger->logError("Some error", "Some other message");
            $logger->logWarning("Some warning", $msg, "Some other message");
            $logger->logDebug("Some debug info", 4, 5);
            $logger->logMessage("Some message");

            $actualError = file_get_contents($this->errorsPath);
            $actualWarning = file_get_contents($this->warningsPath);
            $actualDebug = file_get_contents($this->debugPath);
            $actualMessage = file_get_contents($this->messagesPath);

            // Assert
            $this->assertEquals("Some error Some other message", $actualError);
            $this->assertEquals("Some warning Blah Blah Blah Some other message", $actualWarning);
            $this->assertEquals("Some debug info 4 5", $actualDebug);
            $this->assertEquals("Some  message", $actualMessage);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function tearDown()
    {
        unlink($this->errorsPath);
        unlink($this->debugPath);
        unlink($this->messagesPath);
        unlink($this->warningsPath);
    }
}

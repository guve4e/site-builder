<?php

require_once ("../../../relative-paths.php");
require_once (BUILD_PATH . "/PrintHTML.php");

use PHPUnit\Framework\TestCase;

class PageBuilderTest extends TestCase
{

    public function testPrintDisplayErrorPage()
    {
        $this->expectOutputString('foo');

        PrintHTML::displayErrorPage("Some Message");

    }
}

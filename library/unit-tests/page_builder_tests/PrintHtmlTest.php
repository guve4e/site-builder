<?php

require_once ("../../../relative-paths.php");
require_once (BUILD_PATH . "/PrintHTML.php");

use PHPUnit\Framework\TestCase;

class PageBuilderTest extends TestCase
{

    public function testPrintDisplayErrorPage()
    {

        $expectedString = "<div id='page_content'>\n" .
                          "    <div id='page_content_inner'>\n" .
                          "        <div class='uk-alert uk-alert-danger'>Some Message</div>\n" .
                          "    </div>\n" .
                          "</div>\n";

        $this->expectOutputString($expectedString);

        PrintHTML::displayErrorPage("Some Message");
    }

    public function testPrintOneLink()
    {
        $item = [
            "title" => "some title",
            "id" => "some_id",
            "icon" => "E871"
        ];

        $viewName = "home";

        $expectedString = "<li title='some title'>\n" .
                            "    <a href='?page=some_id'>\n" .
                            "        <span class='menu_icon'><i class='material-icons'>&#xE871;</i></span>\n" .
                            "        <span class='menu_title'>some title</span>\n" .
                            "    </a>\n" .
                            "</li>\n";

        $this->expectOutputString($expectedString);

        PrintHTML::printOneMenuLink($item, $viewName);
    }
}

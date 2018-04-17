<?php

use PHPUnit\Framework\TestCase;
require_once ("../../../relative-paths.php");
require_once (LIBRARY_PATH . "/form/FormExtractor.php");

class FormExtractorTest extends TestCase
{
    protected function setUp()
    {
        // Arrange
        $_GET['entity'] = "product";
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
    }

    public function testDaoAdapterCall()
    {
        try {
            new FormExtractor($_GET, $_POST);
        } catch (Exception $e) {
        }

    }
}



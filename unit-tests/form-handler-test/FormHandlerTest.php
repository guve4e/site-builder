<?php

use PHPUnit\Framework\TestCase;
require_once ("../../relative-paths.php");
require_once(LIBRARY_PATH . "/form/FormHandler.php");

class FormHandlerTest extends TestCase
{
    protected function setUp()
    {
    }

    public function testDaoAdapterCall()
    {
        // Arrange
        $expectedString = "form-handler.php?entity=Product&verb=update&parameter=12334&navigate=1&path_success=product&path_fail=home";

        try {
            $fh = new FormHandler();
            $fh->navigateTo("form-handler")
                ->setEntity("Product")
                ->setVerb("update")
                ->setParameter(12334)
                ->setNavigateAfterUpdate(true)
                ->setPathSuccess("product")
                ->setPathFail("home")
                ->printFormAction();

            $actualString = $fh->getFormActionString();
        } catch (Exception $e) {
        }

        $this->assertEquals($expectedString, $actualString);
    }
}



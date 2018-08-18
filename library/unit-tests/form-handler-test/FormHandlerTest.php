<?php

use PHPUnit\Framework\TestCase;
require_once ("../../../relative-paths.php");
require_once(LIBRARY_PATH . "/form/FormHandler.php");

class FormHandlerTest extends TestCase
{
    protected function setUp()
    {
    }

    /**
     * @throws Exception
     */
    public function testProperCreationOfFOrmString()
    {
        // Arrange
        $expectedString = 'form-handler.php?entity=TestDataResource&verb=update' .
            '&parameter=12334&navigate=1&path_success=product&path_fail=home&' .
            'path_success_params={"product": "1" , "key": "value"}&path_fail_params={"product": "1" , "key": "value"}';

        $fh = new FormHandler();
        $fh->navigateTo("form-handler")
            ->setEntity("TestDataResource")
            ->setVerb("update")
            ->setParameter(12334)
            ->setNavigateAfterUpdate(true)
            ->setPathSuccess("product")
            ->setPathFail("home")
            ->setPathSuccessParameters('product:1/key:value')
            ->setPathFailParameters('product:1/key:value')
            ->printFormAction();

            $actualString = $fh->getFormActionString();

        $this->assertEquals($expectedString, $actualString);
    }
}



<?php

require_once 'src/system/modules/webShop/paymentModules/barzahlen/FormFieldFactory.php';

class FormFieldFactoryTest extends PHPUnit_Framework_TestCase
{
    private $mapping = array
    (
        'foo' => "FormFieldFactoryTest_TestClass",
    );

    public function testCorrectClassIsCreated()
    {
        $formFieldFactory = new FormFieldFactory($this->mapping);
        $object = $formFieldFactory->create("foo", "", "");

        $this->assertInstanceOf("FormFieldFactoryTest_TestClass", $object);
    }

    public function testParameter1IsCorrect()
    {
        $param1 = "Hello Barzahlen";

        $formFieldFactory = new FormFieldFactory($this->mapping);
        $object = $formFieldFactory->create("foo", $param1, "");

        $this->assertEquals($object->param1, $param1);
    }
}

class FormFieldFactoryTest_TestClass
{
    public $param1;

    public function __construct($param1)
    {
        $this->param1 = $param1;
    }
}

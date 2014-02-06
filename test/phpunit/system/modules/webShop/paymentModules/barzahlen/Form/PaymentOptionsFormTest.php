<?php

require_once 'src/system/modules/webShop/paymentModules/barzahlen/Form/PaymentOptionsForm.php';

class PaymentOptionsFormTest extends PHPUnit_Framework_TestCase
{
    private $input;

    protected function setUp()
    {
        $this->input =
            $this->getMock("Input", array("get", "post"), array(),  "", false);
    }

    protected function getForm()
    {
        return new PaymentOptionsForm($this->input);
    }

    public function testGetShippingMethodReturnsCorrectResult()
    {
        $shippingMethod = 2;

        $this->input
            ->expects($this->any())
            ->method("post")
            ->with("shippingMethod")
            ->will($this->returnValue($shippingMethod));

        $form = $this->getForm();
        $this->assertEquals($shippingMethod, $form->getShippingMethod());
    }

    public function testIsSubmitReturnsTrueOnSubmit()
    {
        $this->input
            ->expects($this->any())
            ->method("post")
            ->with("FORM_ACTION")
            ->will($this->returnValue('webShopCheckout'));

        $form = $this->getForm();
        $this->assertTrue($form->isSubmit());
    }

    public function testIsSubmitReturnsFalseOnNoSubmit()
    {
        $backendConfigForm = $this->getForm();
        $this->assertFalse($backendConfigForm->isSubmit());
    }
}

<?php

require_once 'src/system/modules/webShop/paymentModules/barzahlen/Form/BackendConfigForm.php';

class BackendConfigFormTest extends PHPUnit_Framework_TestCase
{
    private $formFieldFactory;
    private $prepareForWidgetWrapper;
    private $input;

    protected function setUp()
    {
        $this->formFieldFactory =
            $this->getMock("FormFieldFactory", array("create"), array(),  "", false);

        $this->prepareForWidgetWrapper =
            $this->getMock("PrepareForWidgetWrapper", array("prepareForWidget"), array(),  "", false);

        $this->input =
            $this->getMock("Input", array("get", "post"), array(),  "", false);
    }

    protected function getBackendConfigForm()
    {
        return
            new BackendConfigForm($this->formFieldFactory, $this->prepareForWidgetWrapper, $this->input);
    }

    public function testGenerateHtmlReturnsAResult()
    {
        $textElement = $this->getMock("TextElement", array("generate"));

        $this->formFieldFactory
            ->expects($this->any())
            ->method("create")
            ->will($this->returnValue($textElement));

        $lang = array
        (
            'shopId' => array
            (
                "shopId",
                "shopId",
            ),
            'paymentKey' => array
            (
                "paymentKey",
                "paymentKey",
            ),
            'notificationKey' => array
            (
                "notificationKey",
                "notificationKey",
            ),
            'sandbox' => array
            (
                "sandbox",
                "sandbox",
            ),
            'maxTotal' => array
            (
                "maxTotal",
                "maxTotal",
            ),
            'minTotal' => array
            (
                "minTotal",
                "minTotal",
            ),
        );

        $config = array
        (
            'shopId' => "foobar",
            'paymentKey' => "barfoo",
            'notificationKey' => "notificationKey",
            'language' => "de",
            'sandbox' => false,
            'minTotal' => 0,
            'maxTotal' => 999,
        );

        $backendConfigForm = $this->getBackendConfigForm();
        $formHtml = $backendConfigForm->generateHtml($config, $lang);

        $this->assertNotEmpty($formHtml);
    }

    public function testGetIdReturnsCorrectId()
    {
        $id = 12345;

        $this->input
            ->expects($this->any())
            ->method("get")
            ->with("id")
            ->will($this->returnValue($id));

        $backendConfigForm = $this->getBackendConfigForm();
        $this->assertEquals($id, $backendConfigForm->getConfigId());
    }

    public function testGetConfigReturnsCorrectConfig()
    {
        $config = array('foo' => "bar");

        $this->input
            ->expects($this->any())
            ->method("post")
            ->with("paymentConfig")
            ->will($this->returnValue($config));

        $backendConfigForm = $this->getBackendConfigForm();
        $this->assertEquals($config, $backendConfigForm->getConfig());
    }

    public function testIsSubmitReturnsTrueOnSubmit()
    {
        $this->input
            ->expects($this->any())
            ->method("post")
            ->with("FORM_SUBMIT")
            ->will($this->returnValue('tl_webshop_paymentmodules'));

        $backendConfigForm = $this->getBackendConfigForm();
        $this->assertTrue($backendConfigForm->isSubmit());
    }

    public function testIsSubmitReturnsFalseOnNoSubmit()
    {
        $backendConfigForm = $this->getBackendConfigForm();
        $this->assertFalse($backendConfigForm->isSubmit());
    }
}

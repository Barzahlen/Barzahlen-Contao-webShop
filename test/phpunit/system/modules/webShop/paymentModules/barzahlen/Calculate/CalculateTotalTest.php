<?php

require_once 'src/system/modules/webShop/paymentModules/barzahlen/Calculate/CalculateTotal.php';

class CalculateTotalTest extends PHPUnit_Framework_TestCase
{
    private $calculateShipping;

    public function setUp()
    {
        $this->calculateShipping =
            $this->getMock("CalculateShipping", array("calc"), array(),  "", false);
    }

    public function testCorrectTotalWillBeCalculated()
    {
        $cartBrutto = 10;
        $shipping = array(
            'brutto' => 11
        );
        $selectedShipping = array();
        $couponSum = 1;
        $taxes = array();

        $expectedTotal = 20;

        $this->calculateShipping
            ->expects($this->any())
            ->method("calc")
            ->will($this->returnValue($shipping));
        ;

        $calculateTotal = new CalculateTotal($this->calculateShipping);
        $total = $calculateTotal->calc(
            $cartBrutto,
            $selectedShipping,
            $couponSum,
            $taxes
        );

        $this->assertEquals($expectedTotal, $total);
    }
}

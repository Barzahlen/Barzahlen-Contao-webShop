<?php

require_once 'src/system/modules/webShop/paymentModules/barzahlen/Calculate/CalculateShipping.php';

class CalculateShippingTest extends PHPUnit_Framework_TestCase
{
    private function getShipping($type, $fee)
    {
        return array
        (
            'shippingPriceType' => $type,
            'shippingFee' => $fee,
            'shippingTax' => 1,
        );
    }

    private function getTaxes()
    {
        return array
        (
            1 => array
            (
                'tax_rate' => 19.00,
            )
        );
    }

    public function testBruttoIsZeroWhenTypeIsShippingInfo()
    {
        $shippingAmount = 0;
        $expectedBrutto = 0;

        $shippingOriginal = $this->getShipping("shippingInfo", $shippingAmount);
        $taxes = $this->getTaxes();

        $calculateShipping = new CalculateShipping(true);
        $shippingNew = $calculateShipping->calc($shippingOriginal, $taxes);

        $this->assertEquals($expectedBrutto, $shippingNew['brutto']);
    }

    public function testNettoIsZeroWhenTypeIsShippingInfo()
    {
        $shippingAmount = 0;
        $expectedNetto = 0;

        $shippingOriginal = $this->getShipping("shippingInfo", $shippingAmount);
        $taxes = $this->getTaxes();

        $calculateShipping = new CalculateShipping(true);
        $shippingNew = $calculateShipping->calc($shippingOriginal, $taxes);

        $this->assertEquals($expectedNetto, $shippingNew['netto']);
    }

    public function testBruttoIsCorrectWhenPricesAreBrutto()
    {
        $shippingAmount = 10;
        $expectedBrutto = 10;

        $shippingOriginal = $this->getShipping("foobar", $shippingAmount);
        $taxes = $this->getTaxes();

        $calculateShipping = new CalculateShipping(true);
        $shippingNew = $calculateShipping->calc($shippingOriginal, $taxes);

        $this->assertEquals($expectedBrutto, $shippingNew['brutto']);
    }

    public function testNettoIsCorrectWhenPricesAreBrutto()
    {
        $shippingAmount = 11.90;
        $expectedNetto = 10;

        $shippingOriginal = $this->getShipping("foobar", $shippingAmount);
        $taxes = $this->getTaxes();

        $calculateShipping = new CalculateShipping(true);
        $shippingNew = $calculateShipping->calc($shippingOriginal, $taxes);

        $this->assertEquals($expectedNetto, $shippingNew['netto']);
    }

    public function testBruttoIsCorrectWhenPricesAreNetto()
    {
        $shippingAmount = 10;
        $expectedBrutto = 11.90;

        $shippingOriginal = $this->getShipping("foobar", $shippingAmount);
        $taxes = $this->getTaxes();

        $calculateShipping = new CalculateShipping(false);
        $shippingNew = $calculateShipping->calc($shippingOriginal, $taxes);

        $this->assertEquals($expectedBrutto, $shippingNew['brutto']);
    }

    public function testNettoIsCorrectWhenPricesAreNetto()
    {
        $shippingAmount = 10;
        $expectedNetto = 10;

        $shippingOriginal = $this->getShipping("foobar", $shippingAmount);
        $taxes = $this->getTaxes();

        $calculateShipping = new CalculateShipping(false);
        $shippingNew = $calculateShipping->calc($shippingOriginal, $taxes);

        $this->assertEquals($expectedNetto, $shippingNew['netto']);
    }
}

<?php

/**
 * Calculates shipping amount
 */
class CalculateShipping
{
    /**
     * @var bool
     */
    private $pricesAreBrutto;

    /**
     * @var array
     */
    private $shipping;

    /**
     * @param bool $pricesAreBrutto
     */
    public function __construct($pricesAreBrutto)
    {
        $this->pricesAreBrutto = $pricesAreBrutto;
    }

    /**
     * Adds shipping costs in brutto and netto to the shipping array
     *
     * @param  array $shipping
     * @param  array $taxes
     * @return array
     */
    public function calc($shipping, $taxes)
    {
        $this->shipping = $shipping;

        if ($shipping['shippingPriceType'] == 'shippingInfo') {
            $shipping['netto'] = 0;
            $shipping['brutto'] = 0;
        } else {
            if ($this->pricesAreBrutto) {
                $shipping['netto'] =
                    $shipping['shippingFee'] / ($taxes[$shipping['shippingTax']]['tax_rate'] / 100 + 1);
                $shipping['brutto'] = $shipping['shippingFee'];
            } else {
                $shipping['netto'] = $shipping['shippingFee'];
                $shipping['brutto'] =
                    $shipping['shippingFee'] * ($taxes[$shipping['shippingTax']]['tax_rate'] / 100 + 1);
            }
        }

        return $shipping;
    }
}

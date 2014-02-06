<?php

/**
 * Calculates total order amount
 */
class CalculateTotal
{
    /**
     * @var CalculateShipping
     */
    private $calculateShipping;

    /**
     * @param CalculateShipping $calculateShipping
     */
    public function __construct($calculateShipping)
    {
        $this->calculateShipping = $calculateShipping;
    }

    /**
     * Calculates total order amount
     *
     * @param  string $cartSum
     * @param  array $selectedShipping
     * @param  string $couponSum
     * @param  array $taxes
     * @return string
     */
    public function calc($cartSum, $selectedShipping, $couponSum, $taxes)
    {
        $shipping = $this->calculateShipping->calc($selectedShipping, $taxes);

        $total = $cartSum + $shipping['brutto'] - $couponSum;

        return $total;
    }
}

<?php

/**
 * Access for checkout payment select form
 */
class PaymentOptionsForm
{
    /**
     * @var Input
     */
    private $input;

    /**
     * @param Input $input
     */
    public function __construct($input)
    {
        $this->input = $input;
    }

    /**
     * Get shipping method from submitted form
     *
     * @return string
     */
    public function getShippingMethod()
    {
        return $this->input->post("shippingMethod");
    }

    /**
     * Has form submitted
     *
     * @return bool
     */
    public function isSubmit()
    {
        return $this->input->post('FORM_ACTION') == 'webShopCheckout';
    }
}

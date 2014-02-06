<?php

/**
 * Responsible for transferring order to Barzahlen and mark it as transferred
 */
class BarzahlenPayment
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var array
     */
    private $order;
    /**
     * @var Barzahlen_Api
     */
    private $api;
    /**
     * @var Barzahlen_Request_Payment
     */
    private $paymentRequest;

    /**
     * @param OrderRepository $orderRepository
     */
    public function __construct($orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Transfers order to Barzahlen, mark it as transferred
     *
     * @param array $order
     * @param Barzahlen_Api $api
     * @param Barzahlen_Request_Payment $paymentRequest
     */
    public function handlePayment($order, $api, $paymentRequest)
    {
        $this->order = $order;
        $this->api = $api;
        $this->paymentRequest = $paymentRequest;

        if ($order['orderStatus'] != "cancel" && !$this->isAlreadyTransfered()) {
            $this->transfer($this->api, $this->paymentRequest);
            $this->markOrderAsTransfered();
        }
    }

    /**
     * Checks if order is already transferred to Barzahlen
     *
     * @return bool
     */
    private function isAlreadyTransfered()
    {
        return
            array_key_exists("barzahlen", $this->order['paymentMethodData']) &&
            array_key_exists("transfered", $this->order['paymentMethodData']['barzahlen']) &&
            $this->order['paymentMethodData']['barzahlen']['transfered'];
    }

    /**
     * Transfers order to Barzahlen
     */
    private function transfer()
    {
        $this->api->handleRequest($this->paymentRequest);
    }

    /**
     * Marks order as transferred in database
     */
    private function markOrderAsTransfered()
    {
        $barzahlenPaymentData = array();

        $barzahlenPaymentData['transfered'] = true;
        $barzahlenPaymentData['expirationNotice'] = $this->paymentRequest->getExpirationNotice();
        $barzahlenPaymentData['transactionId'] = $this->paymentRequest->getTransactionId();
        $barzahlenPaymentData['infotext1'] = $this->paymentRequest->getInfotext1();
        $barzahlenPaymentData['infotext2'] = $this->paymentRequest->getInfotext2();

        $this->order['paymentMethodData']['barzahlen']  = $barzahlenPaymentData;

        $this->orderRepository->updatePaymentMethodDataById(
            $this->order['id'],
            $this->order['paymentMethodData']
        );
    }

    /**
     * Get Barzahlen payment info
     *
     * @return string
     */
    public function getPaymentInfo()
    {
        return $this->order['paymentMethodData']['barzahlen']['infotext1'];
    }
}

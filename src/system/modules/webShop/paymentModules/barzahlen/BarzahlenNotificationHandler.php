<?php

/**
 * Handles notifications which we get via Barzahlen
 */
class BarzahlenNotificationHandler
{
    const NOTIFICATION_PAYMENT = "payment";
    const NOTIFICATION_PAYMENT_STATUS_PAID = "paid";
    const NOTIFICATION_PAYMENT_STATUS_EXPIRED = "expired";

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var array
     */
    private $order;
    /**
     * @var Barzahlen_Notification
     */
    private $notification;

    /**
     * @param OrderRepository $orderRepository
     */
    public function __construct($orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Handles the notification and updates order depending of the notification type
     *
     * @param array $order
     * @param Barzahlen_Notification $notification
     */
    public function handleNotification($order, $notification)
    {
        $this->order = $order;
        $this->notification = $notification;

        $this->notification->validate();

        switch ($this->notification->getNotificationType()) {
            case self::NOTIFICATION_PAYMENT:
                $this->handlePaymentNotification();
                break;
        }
    }

    private function handlePaymentNotification()
    {
        $this->validatePaymentNotification();

        switch ($this->notification->getState()) {
            case self::NOTIFICATION_PAYMENT_STATUS_PAID:
                $this->orderRepository->markOrderAsPaid($this->order['id']);
                break;
            case self::NOTIFICATION_PAYMENT_STATUS_EXPIRED:
                $this->orderRepository->markOrderAsCanceled($this->order['id']);
                break;
        }
    }

    private function validatePaymentNotification()
    {
        $barzahlenPaymentData = $this->order['paymentMethodData']['barzahlen'];

        if ($this->notification->getTransactionId() != $barzahlenPaymentData['transactionId']) {
            throw new RuntimeException("Invalid transaction-id in notification!");
        }
    }
}

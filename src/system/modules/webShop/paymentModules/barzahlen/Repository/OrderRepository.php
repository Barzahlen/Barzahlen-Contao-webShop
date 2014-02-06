<?php

/**
 * Repository to access orders in the database
 */
class OrderRepository
{
    /**
     * @var array
     */
    private static $serializedFields = array
    (
        'shippingAddress',
        'shippingMethodData',
        'shippingMethodDatashippingZones',
        'shippingMethodDatashippingPricesWeight',
        'shippingMethodDatashippingPricesPrice',
        'paymentAddress',
        'paymentMethodData',
        'paymentMethodDatapaymentConfig',
        'paymentMethodDatagroups',
        'coupons',
        'taxes',
    );

    /**
     * @var Database
     */
    private $Database;

    /**
     * @var SerializedFieldsHelper
     */
    private $serializedFieldsHelper;

    /**
     * @param Database $database
     * @param SerializedFieldsHelper $serializedFieldsHelper
     */
    public function __construct($database, $serializedFieldsHelper)
    {
        $this->Database = $database;

        $this->serializedFieldsHelper = $serializedFieldsHelper;
        $this->serializedFieldsHelper->setSerializedFields(self::$serializedFields);
    }

    /**
     * Returns an order by id as array or null if not found
     *
     * @param  int $orderId
     * @return array|null
     */
    public function getOrderById($orderId)
    {
        $row = $this->Database
            ->prepare("SELECT * FROM tl_webshop_orders WHERE id=?")
            ->execute($orderId)
            ->fetchAssoc();

        if ($row) {
            $order = $this->serializedFieldsHelper->unserializeArray($row);
        } else {
            $order = null;
        }

        return $order;
    }

    /**
     * Serialize and update paymentMethodData
     *
     * @param int $orderId
     * @param array $paymentMethodData
     */
    public function updatePaymentMethodDataById($orderId, $paymentMethodData)
    {
        $paymentMethodDataSerialized =
            $this->serializedFieldsHelper->serializeField("paymentMethodData", $paymentMethodData);

        $this->Database
            ->prepare("UPDATE tl_webshop_orders set paymentMethodData=? WHERE id=?")
            ->execute($paymentMethodDataSerialized, $orderId);
    }

    /**
     * Marks an order as payed
     *
     * @param int $orderId
     */
    public function markOrderAsPaid($orderId)
    {
        $this->Database
            ->prepare("UPDATE tl_webshop_orders set payed=? WHERE id=?")
            ->execute(1, $orderId);
    }

    /**
     * Marks an order as canceled
     *
     * @param int $orderId
     */
    public function markOrderAsCanceled($orderId)
    {
        $this->Database
            ->prepare("UPDATE tl_webshop_orders set orderStatus=? WHERE id=?")
            ->execute("cancel", $orderId);
    }
}

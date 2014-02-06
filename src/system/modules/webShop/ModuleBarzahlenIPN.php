<?php

require_once '../../initialize.php';

require_once(TL_ROOT . '/system/modules/webShop/paymentModules/barzahlen/include.php');

/**
 * Will be called from Barzahlen when there is some notification
 */
class ModuleBarzahlenNotify extends Controller
{
    /**
     * @var int
     */
    private $orderId;
    /**
     * @var array
     */
    private $order;

    public function __construct()
    {
        $this->Import('Database');
        $this->Import('Config');
        $this->Import('Input');

        $serializedFieldsHelper = new SerializedFieldsHelper();
        $this->orderRepository = new OrderRepository($this->Database, $serializedFieldsHelper);
    }

    public function run()
    {
        try {
            $this->fetchOrderId();
            $this->fetchOrder();
            $this->handleNotification();
        } catch (Exception $e) {
            if ($this->orderId) {
                $orderIdLog = " - order_id: " . $this->orderId;
            } else {
                $orderIdLog = "";
            }

            $this->log(
                "Error on notification from barzahlen - " .
                $e->getMessage() .
                $orderIdLog . " - " .
                $_SERVER['QUERY_STRING'],
                "ModuleBarzahlenNotify::run",
                TL_ERROR
            );

            echo $e->getMessage();
        }
    }

    private function fetchOrderId()
    {
        if ($this->Input->get("order_id")) {
            $this->orderId = $this->Input->get("order_id");
        } else {
            throw new Exception("No orderId");
        }
    }

    private function fetchOrder()
    {
        $this->order = $this->orderRepository->getOrderById($this->orderId);

        if (!$this->order) {
            throw new Exception("No order found");
        }
    }

    private function handleNotification()
    {
        $notification = new Barzahlen_Notification(
            $this->order['paymentMethodData']['paymentConfig']['shopId'],
            $this->order['paymentMethodData']['paymentConfig']['notificationKey'],
            $_GET
        );

        $notificationHandler = new BarzahlenNotificationHandler($this->orderRepository);
        $notificationHandler->handleNotification($this->order, $notification);
    }
}

$obBarzahlenNotify = new ModuleBarzahlenNotify();
$obBarzahlenNotify->run();

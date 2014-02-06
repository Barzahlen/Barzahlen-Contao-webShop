<?php

require_once 'src/system/modules/webShop/paymentModules/barzahlen/BarzahlenNotificationHandler.php';

class BarzahlenNotificationHandlerTest extends PHPUnit_Framework_TestCase
{
    private $orderRepository;
    private $notify;

    public function setUp()
    {
        $this->orderRepository =
            $this->getMock(
				"OrderRepository", array("markOrderAsPaid", "markOrderAsCanceled"), array(),  "", false);

        $this->notify =
            $this->getMock(
				"Barzahlen_Notify",
				array("validate",
					"getNotificationType",
					"getState",
					"getOrderId",
					"getCustomerEmail",
                    "getTransactionId"
				), array(),  "", false);
    }

    public function testValidateIsCalled()
    {
        $this->notify
            ->expects($this->atLeastOnce())
            ->method("validate");

        $notificationHandler = new BarzahlenNotificationHandler($this->orderRepository);
        $notificationHandler->handleNotification(array("order"), $this->notify);
    }

    public function testOrderIsMarkedAsPayedOnPaymentNotification()
    {
		$orderId = 123;
        $transactionId = 1235;
		$email = "foo@example.com";

        $order = array(
            'id' => $orderId,
            'email' => 1,
            'paymentMethodData' => array(
                'barzahlen' => array(
                    'transactionId' => $transactionId
                )
            )
        );

        $this->notify
            ->expects($this->any())
            ->method("getNotificationType")
            ->will($this->returnValue(BarzahlenNotificationHandler::NOTIFICATION_PAYMENT));

		$this->notify
			->expects($this->any())
			->method("getState")
			->will($this->returnValue(BarzahlenNotificationHandler::NOTIFICATION_PAYMENT_STATUS_PAID));

		$this->notify
			->expects($this->any())
			->method("getOrderId")
			->will($this->returnValue($orderId));

        $this->notify
            ->expects($this->any())
            ->method("getTransactionId")
            ->will($this->returnValue($transactionId));

		$this->notify
			->expects($this->any())
			->method("getCustomerEmail")
			->will($this->returnValue($email));

        $this->orderRepository
            ->expects($this->atLeastOnce())
            ->method("markOrderAsPaid")
            ->with($orderId);

        $notificationHandler = new BarzahlenNotificationHandler($this->orderRepository);
        $notificationHandler->handleNotification($order, $this->notify);
    }

	public function testOrderIsMarkedAsCanceledOnExpiredNotification()
	{
        $orderId = 123;
        $transactionId = 1235;
		$email = "foo@example.com";

        $order = array(
            'id' => $orderId,
            'email' => 1,
            'paymentMethodData' => array(
                'barzahlen' => array(
                    'transactionId' => $transactionId
                )
            )
        );

		$this->notify
			->expects($this->any())
			->method("getNotificationType")
			->will($this->returnValue(BarzahlenNotificationHandler::NOTIFICATION_PAYMENT));

		$this->notify
			->expects($this->any())
			->method("getState")
			->will($this->returnValue(BarzahlenNotificationHandler::NOTIFICATION_PAYMENT_STATUS_EXPIRED));

		$this->notify
			->expects($this->any())
			->method("getOrderId")
			->will($this->returnValue($orderId));

        $this->notify
            ->expects($this->any())
            ->method("getTransactionId")
            ->will($this->returnValue($transactionId));

		$this->notify
			->expects($this->any())
			->method("getCustomerEmail")
			->will($this->returnValue($email));

		$this->orderRepository
			->expects($this->atLeastOnce())
			->method("markOrderAsCanceled")
			->with($orderId);

		$notificationHandler = new BarzahlenNotificationHandler($this->orderRepository);
		$notificationHandler->handleNotification($order, $this->notify);
	}

	public function testExceptionIsThrownOnWrongTransactionIdAtPayedNotification()
	{
        $transactionId = 123;

        $order = array(
            'id' => 1,
            'email' => 1,
            'paymentMethodData' => array(
                'barzahlen' => array(
                    'transactionId' => $transactionId
                )
            )
        );

        $this->notify
			->expects($this->any())
			->method("getNotificationType")
			->will($this->returnValue(BarzahlenNotificationHandler::NOTIFICATION_PAYMENT));

		$this->notify
			->expects($this->any())
			->method("getState")
			->will($this->returnValue(BarzahlenNotificationHandler::NOTIFICATION_PAYMENT_STATUS_PAID));

        $this->notify
            ->expects($this->any())
            ->method("getTransactionId")
            ->will($this->returnValue(44));

		$this->setExpectedException("RuntimeException");

		$notificationHandler = new BarzahlenNotificationHandler($this->orderRepository);
		$notificationHandler->handleNotification($order, $this->notify);
	}

	public function testExceptionIsThrownOnWrongTransactionIdAtExpiredNotification()
	{
		$transactionId = 1234;

		$order = array(
            'id' => 1,
			'email' => 1,
            'paymentMethodData' => array(
                'barzahlen' => array(
                    'transactionId' => $transactionId
                )
            )
		);

		$this->notify
			->expects($this->any())
			->method("getNotificationType")
			->will($this->returnValue(BarzahlenNotificationHandler::NOTIFICATION_PAYMENT));

		$this->notify
			->expects($this->any())
			->method("getState")
			->will($this->returnValue(BarzahlenNotificationHandler::NOTIFICATION_PAYMENT_STATUS_EXPIRED));

		$this->notify
            ->expects($this->any())
            ->method("getTransactionId")
            ->will($this->returnValue(123));

		$this->setExpectedException("RuntimeException");

		$notificationHandler = new BarzahlenNotificationHandler($this->orderRepository);
		$notificationHandler->handleNotification($order, $this->notify);
	}
}

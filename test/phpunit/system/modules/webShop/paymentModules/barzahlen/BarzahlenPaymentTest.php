<?php

require_once 'src/system/modules/webShop/paymentModules/barzahlen/BarzahlenPayment.php';

class BarzahlenPaymentTest extends PHPUnit_Framework_TestCase
{
    private $orderRepository;
    private $api;
    private $request;

    public function setUp()
    {
        $this->orderRepository =
            $this->getMock("OrderRepository", array("updatePaymentMethodDataById"), array(),  "", false);

        $this->api =
            $this->getMock("Barzahlen_Api", array("handleRequest"), array(),  "", false);

        $this->request =
            $this->getMock("Barzahlen_Request_Payment", array("getTransactionId", "getExpirationNotice", "getInfotext1", "getInfotext2"), array(),  "", false);
    }

    public function testIfOrderWillBeTransfered()
    {
        $order = array(
            'id' => 123,
            'paymentMethodData' => array(),
			'orderStatus' => "new",
        );

        $this->api
            ->expects($this->once())
            ->method("handleRequest")
            ->with($this->request);

        $barzahlenPayment = new BarzahlenPayment($this->orderRepository);
        $barzahlenPayment->handlePayment($order, $this->api, $this->request);
    }

	public function testIfCanceledOrderWillNotBeTransfered()
	{
		$order = array(
			'id' => 123,
			'paymentMethodData' => array(),
			'orderStatus' => "cancel",
		);

		$this->api
			->expects($this->never())
			->method("handleRequest");

		$barzahlenPayment = new BarzahlenPayment($this->orderRepository);
		$barzahlenPayment->handlePayment($order, $this->api, $this->request);
	}

    public function testIfOrderWillBeMarkedAsTransfered()
    {
        $order = array
        (
            'id' => 123,
            'paymentMethodData' => array(),
			'orderStatus' => "new",
        );

        $infotext1 = "Zahlung erfolgreich";
        $infotext2 = "Zahlung erfolgreich";
        $transactionId = "123";
        $expirationNotice = "123";

        $expectedPaymentMethodData = array
        (
            'barzahlen' => array
            (
                'transfered' => true,
                'infotext1' => $infotext1,
                'infotext2' => $infotext2,
                'transactionId' => $transactionId,
                'expirationNotice' => $expirationNotice,
            ),
        );

        $this->request
            ->expects($this->any())
            ->method("getInfotext1")
            ->will($this->returnValue($infotext1));

        $this->request
            ->expects($this->any())
            ->method("getInfotext2")
            ->will($this->returnValue($infotext2));

        $this->request
            ->expects($this->any())
            ->method("getTransactionId")
            ->will($this->returnValue($transactionId));

        $this->request
            ->expects($this->any())
            ->method("getExpirationNotice")
            ->will($this->returnValue($expirationNotice));

        $this->orderRepository
            ->expects($this->atLeastOnce())
            ->method("updatePaymentMethodDataById")
            ->with($order['id'], $expectedPaymentMethodData);

        $barzahlenPayment = new BarzahlenPayment($this->orderRepository);
        $barzahlenPayment->handlePayment($order, $this->api, $this->request);
    }

    public function testIfCorrectPaymentInfoWillBeReturned()
    {
        $order = array
        (
            'id' => 123,
            'paymentMethodData' => array(),
			'orderStatus' => "new",
        );

        $infotext = "Zahlung erfolgreich";

        $this->request
            ->expects($this->any())
            ->method("getInfotext1")
            ->will($this->returnValue($infotext));

        $barzahlenPayment = new BarzahlenPayment($this->orderRepository);
        $barzahlenPayment->handlePayment($order, $this->api, $this->request);

        $this->assertEquals($infotext, $barzahlenPayment->getPaymentInfo());
    }

    public function testIfOrderWontBeTransferedWhenAlreadyTransfered()
    {
        $order = array
        (
            'id' => 123,
            'paymentMethodData' => array
            (
                'barzahlen' => array
                (
                    'transfered' => true,
                    'infotext1' => "foobar",
                ),
            ),
			'orderStatus' => "new",
        );

        $this->api
            ->expects($this->never())
            ->method("handleRequest");

        $barzahlenPayment = new BarzahlenPayment($this->orderRepository);
        $barzahlenPayment->handlePayment($order, $this->api, $this->request);
    }

    public function testIfCorrectPaymentInfoWillBeReturnedWhenAlreadyTransfered()
    {
        $infotext = "Zahlung erfolgreich";

        $order = array
        (
            'id' => 123,
            'paymentMethodData' => array
            (
                'barzahlen' => array
                (
                    'transfered' => true,
                    'infotext1' => $infotext,
                ),
            ),
			'orderStatus' => "new",
        );

        $barzahlenPayment = new BarzahlenPayment($this->orderRepository);
        $barzahlenPayment->handlePayment($order, $this->api, $this->request);

        $this->assertEquals($infotext, $barzahlenPayment->getPaymentInfo());
    }
}

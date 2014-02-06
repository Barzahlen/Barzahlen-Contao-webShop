<?php

require_once 'src/system/modules/webShop/paymentModules/barzahlen/Repository/OrderRepository.php';

class OrderRepositoryTest extends PHPUnit_Framework_TestCase
{
    private $database;
    private $databaseStmt;
    private $databaseResult;
    private $serializedFieldsHelper;

    protected function setUp()
    {
        $this->database =
            $this->getMock("Database", array("prepare"), array(),  "", false);

        $this->databaseStmt =
            $this->getMock("Database_Statement", array("execute"), array(),  "", false);

        $this->databaseResult =
            $this->getMock("Database_Result", array("fetchAssoc"), array(),  "", false);

        $this->serializedFieldsHelper =
            $this->getMock("SerializedFieldsHelper", array("setSerializedFields", "unserializeArray"), array(),  "", false);
    }

    public function testSerializedFieldsHelperGetsConfig()
    {
        $this->serializedFieldsHelper
            ->expects($this->atLeastOnce())
            ->method("setSerializedFields");

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
    }

    public function testGetOrderByIdWithCorrectQueryWillBePrepared()
    {
        $this->databaseStmt
            ->expects($this->any())
            ->method("execute")
            ->will($this->returnValue($this->databaseResult));

        $this->database
            ->expects($this->atLeastOnce())
            ->method("prepare")
            ->with("SELECT * FROM tl_webshop_orders WHERE id=?")
            ->will($this->returnValue($this->databaseStmt));

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
        $orderRepository->getOrderById(123);
    }

    public function testGetOrderByIdWithCorrectParametersWillBeExecuted()
    {
        $orderId = 123;

        $this->databaseStmt
            ->expects($this->atLeastOnce())
            ->method("execute")
            ->with($orderId)
            ->will($this->returnValue($this->databaseResult));

        $this->database
            ->expects($this->any())
            ->method("prepare")
            ->will($this->returnValue($this->databaseStmt));

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
        $orderRepository->getOrderById($orderId);
    }

    public function testGetOrderByIdResultWillBeFetched()
    {
        $this->databaseResult
            ->expects($this->atLeastOnce())
            ->method("fetchAssoc");

        $this->databaseStmt
            ->expects($this->any())
            ->method("execute")
            ->will($this->returnValue($this->databaseResult));

        $this->database
            ->expects($this->any())
            ->method("prepare")
            ->will($this->returnValue($this->databaseStmt));

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
        $orderRepository->getOrderById(123);
    }

    public function testGetOrderByIdCorrectResultWillBePassedToSerializedFieldsHelper()
    {
        $order = array("hello", "barzahlen");

        $this->serializedFieldsHelper
            ->expects($this->atLeastOnce())
            ->method("unserializeArray")
            ->with($order);

        $this->databaseResult
            ->expects($this->any())
            ->method("fetchAssoc")
            ->will($this->returnValue($order));

        $this->databaseStmt
            ->expects($this->any())
            ->method("execute")
            ->will($this->returnValue($this->databaseResult));

        $this->database
            ->expects($this->any())
            ->method("prepare")
            ->will($this->returnValue($this->databaseStmt));

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
        $orderRepository->getOrderById(123);
    }

    public function testGetOrderByIdCorrectResultWillBeReturned()
    {
        $order = array("hello", "barzahlen");

        $this->serializedFieldsHelper
            ->expects($this->any())
            ->method("unserializeArray")
            ->will($this->returnValue($order));

        $this->databaseResult
            ->expects($this->any())
            ->method("fetchAssoc")
            ->will($this->returnValue($order));

        $this->databaseStmt
            ->expects($this->any())
            ->method("execute")
            ->will($this->returnValue($this->databaseResult));

        $this->database
            ->expects($this->any())
            ->method("prepare")
            ->will($this->returnValue($this->databaseStmt));

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
        $result = $orderRepository->getOrderById(123);

        $this->assertEquals($order, $result);
    }

    public function testGetOrderByIdNullWillBeReturnedWhenNoResult()
    {
        $this->databaseResult
            ->expects($this->any())
            ->method("fetchAssoc")
            ->will($this->returnValue(false));

        $this->databaseStmt
            ->expects($this->any())
            ->method("execute")
            ->will($this->returnValue($this->databaseResult));

        $this->database
            ->expects($this->any())
            ->method("prepare")
            ->will($this->returnValue($this->databaseStmt));

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
        $result = $orderRepository->getOrderById(123);

        $this->assertEquals(null, $result);
    }

    public function testUpdatePaymentMethodDataByIdWithCorrectQueryWillBePrepared()
    {
        $this->databaseStmt
            ->expects($this->any())
            ->method("execute")
            ->will($this->returnValue($this->databaseResult));

        $this->database
            ->expects($this->atLeastOnce())
            ->method("prepare")
            ->with("UPDATE tl_webshop_orders set paymentMethodData=? WHERE id=?")
            ->will($this->returnValue($this->databaseStmt));

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
        $orderRepository->updatePaymentMethodDataById(123, "foobar");
    }

    public function testUpdatePaymentMethodDataByIdWithCorrectParametersWillBeExecuted()
    {
        $orderId = 123;
        $data = "foobar";

        $this->databaseStmt
            ->expects($this->atLeastOnce())
            ->method("execute")
            ->with($data, $orderId)
            ->will($this->returnValue($this->databaseResult));

        $this->database
            ->expects($this->any())
            ->method("prepare")
            ->will($this->returnValue($this->databaseStmt));

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
        $orderRepository->updatePaymentMethodDataById($orderId, $data);
    }

    public function testMarkOrderAsPayedWithCorrectQueryWillBePrepared()
    {
        $this->databaseStmt
            ->expects($this->any())
            ->method("execute")
            ->will($this->returnValue($this->databaseResult));

        $this->database
            ->expects($this->atLeastOnce())
            ->method("prepare")
            ->with("UPDATE tl_webshop_orders set payed=? WHERE id=?")
            ->will($this->returnValue($this->databaseStmt));

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
        $orderRepository->markOrderAsPaid(123);
    }

    public function testMarkOrderAsPayedWithCorrectParametersWillBeExecuted()
    {
        $orderId = 123;

        $this->databaseStmt
            ->expects($this->atLeastOnce())
            ->method("execute")
            ->with(1, $orderId)
            ->will($this->returnValue($this->databaseResult));

        $this->database
            ->expects($this->any())
            ->method("prepare")
            ->will($this->returnValue($this->databaseStmt));

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
        $orderRepository->markOrderAsPaid($orderId);
    }

    public function testMarkOrderAsCanceledWithCorrectQueryWillBePrepared()
    {
        $this->databaseStmt
            ->expects($this->any())
            ->method("execute")
            ->will($this->returnValue($this->databaseResult));

        $this->database
            ->expects($this->atLeastOnce())
            ->method("prepare")
            ->with("UPDATE tl_webshop_orders set orderStatus=? WHERE id=?")
            ->will($this->returnValue($this->databaseStmt));

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
        $orderRepository->markOrderAsCanceled(123);
    }

    public function testMarkOrderAsCanceledWithCorrectParametersWillBeExecuted()
    {
        $orderId = 123;

        $this->databaseStmt
            ->expects($this->atLeastOnce())
            ->method("execute")
            ->with("cancel", $orderId)
            ->will($this->returnValue($this->databaseResult));

        $this->database
            ->expects($this->any())
            ->method("prepare")
            ->will($this->returnValue($this->databaseStmt));

        $orderRepository = new OrderRepository($this->database, $this->serializedFieldsHelper);
        $orderRepository->markOrderAsCanceled($orderId);
    }
}

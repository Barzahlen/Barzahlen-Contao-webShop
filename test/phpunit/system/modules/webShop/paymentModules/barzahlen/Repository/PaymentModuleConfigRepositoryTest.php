<?php

require_once 'src/system/modules/webShop/paymentModules/barzahlen/Repository/PaymentModuleConfigRepository.php';

class PaymentModuleConfigRepositoryTest extends PHPUnit_Framework_TestCase
{
    private $database;
    private $databaseStmt;

    protected function setUp()
    {
        $this->database =
            $this->getMock("Database", array("prepare"), array(),  "", false);

        $this->databaseStmt =
            $this->getMock("Database_Statement", array("execute"), array(),  "", false);
    }

    public function testOnUpdateCorrectQueryWillBePrepared()
    {
        $this->database
            ->expects($this->atLeastOnce())
            ->method("prepare")
            ->with("UPDATE tl_webshop_paymentmodules SET paymentConfig=? WHERE id=?")
            ->will($this->returnValue($this->databaseStmt));

        $paymentModuleConfigRepository = new PaymentModuleConfigRepository($this->database);
        $paymentModuleConfigRepository->updateConfigById(1, array(123));
    }

    public function testOnUpdateStatementWithCorrectParametersWillBeExecuted()
    {
        $id = 1;
        $config = array(1, 2, 3);

        $this->databaseStmt
            ->expects($this->atLeastOnce())
            ->method("execute")
            ->with(serialize($config), $id);

        $this->database
            ->expects($this->any())
            ->method("prepare")
            ->will($this->returnValue($this->databaseStmt));

        $paymentModuleConfigRepository = new PaymentModuleConfigRepository($this->database);
        $paymentModuleConfigRepository->updateConfigById($id, $config);
    }
}

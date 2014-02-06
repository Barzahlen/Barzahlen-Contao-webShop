<?php

require_once 'src/system/modules/webShop/paymentModules/barzahlen/Validate/ValidateTotal.php';

class ValidateTotalTest extends PHPUnit_Framework_TestCase
{
    public function testValidateReturnsTrueWhenTotalIsLessThanMaxTotal()
    {
        $total = 5;
        $minTotal = 0;
        $maxTotal = 10;

        $validateTotal = new ValidateTotal(array('error_total_to_high' => "foobar"));
        $valid = $validateTotal->validate($total, $minTotal, $maxTotal);

        $this->assertTrue($valid);
    }

    public function testValidateReturnsTrueWhenNoMaxTotal()
    {
        $total = 5;
        $minTotal = 0;
        $maxTotal = false;

        $validateTotal = new ValidateTotal(array('error_total_to_high' => "foobar"));
        $valid = $validateTotal->validate($total, $minTotal, $maxTotal);

        $this->assertTrue($valid);
    }

    public function testValidateReturnsTrueWhenTotalIsEqualToMaxTotal()
    {
        $total = 10;
        $minTotal = 0;
        $maxTotal = 10;

        $validateTotal = new ValidateTotal(array('error_total_to_high' => "foobar"));
        $valid = $validateTotal->validate($total, $minTotal, $maxTotal);

        $this->assertTrue($valid);
    }

    public function testValidateReturnsFalseWhenTotalIsBiggerThanMaxTotal()
    {
        $total = 11;
        $minTotal = 0;
        $maxTotal = 10;

        $validateTotal = new ValidateTotal(array('foobar'));
        $valid = $validateTotal->validate($total, $minTotal, $maxTotal);

        $this->assertFalse($valid);
    }


    public function testValidateReturnsFalseWhenTotalIsLessThanMinTotal()
    {
        $total = 5;
        $minTotal = 10;
        $maxTotal = 0;

        $validateTotal = new ValidateTotal(array('error_total_to_high' => "foobar"));
        $valid = $validateTotal->validate($total, $minTotal, $maxTotal);

        $this->assertFalse($valid);
    }

    public function testValidateReturnsTrueWhenNoMinTotal()
    {
        $total = 5;
        $minTotal = false;
        $maxTotal = 10;

        $validateTotal = new ValidateTotal(array('error_total_to_high' => "foobar"));
        $valid = $validateTotal->validate($total, $minTotal, $maxTotal);

        $this->assertTrue($valid);
    }

    public function testValidateReturnsTrueWhenTotalIsEqualToMinTotal()
    {
        $total = 10;
        $minTotal = 10;
        $maxTotal = 15;

        $validateTotal = new ValidateTotal(array('error_total_to_high' => "foobar"));
        $valid = $validateTotal->validate($total, $minTotal, $maxTotal);

        $this->assertTrue($valid);
    }

    public function testValidateReturnsTrueWhenTotalIsBiggerThanMinTotal()
    {
        $total = 11;
        $minTotal = 10;
        $maxTotal = 15;

        $validateTotal = new ValidateTotal(array('foobar'));
        $valid = $validateTotal->validate($total, $minTotal, $maxTotal);

        $this->assertTrue($valid);
    }


    public function testValidateReturnsTextOnErrorMessage()
    {
        $total = 11;
        $minTotal = 0;
        $maxTotal = 10;

        $validateTotal = new ValidateTotal(array('error_total_to_high' => "foobar"));
        $valid = $validateTotal->validate($total, $minTotal, $maxTotal);
        $errorMessage = $validateTotal->getErrorMessage();

        $this->assertNotEmpty($errorMessage);
    }
}

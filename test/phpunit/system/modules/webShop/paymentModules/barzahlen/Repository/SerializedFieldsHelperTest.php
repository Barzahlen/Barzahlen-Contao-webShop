<?php

require_once 'src/system/modules/webShop/paymentModules/barzahlen/Repository/SerializedFieldsHelper.php';

class SerializedFieldsHelperTest extends PHPUnit_Framework_TestCase
{
    public function testSerializeArrayWithNoConfigReturnsSameData()
    {
        $dataPlain = array
        (
            "hello",
            "barzahlen",
        );

        $serializedFieldsHelper = new SerializedFieldsHelper();
        $dataSerialized = $serializedFieldsHelper->serializeArray($dataPlain);

        $this->assertEquals($dataPlain, $dataSerialized);
    }

    public function testSerializeArrayWithConfigReturnsDataSerialized()
    {
        $key = "hello";
        $value = "barzahlen";

        $dataPlain = array
        (
            $key => array
            (
                $value,
            ),
        );

        $dataExpected = array
        (
            $key => serialize(
                array
                (
                    $value,
                )
            ),
        );

        $serializedFieldsHelper = new SerializedFieldsHelper();
        $serializedFieldsHelper->setSerializedFields(
            array
            (
                $key,
            )
        );
        $dataSerialized = $serializedFieldsHelper->serializeArray($dataPlain);

        $this->assertEquals($dataExpected, $dataSerialized);
    }

    public function testSerializeRecursiveArrayWithConfigReturnsDataSerialized()
    {
        $key1 = "hello";
        $key2 = "barzahlen";
        $value = "foobar";

        $dataPlain = array
        (
            $key1 => array
            (
                $key2 => array
                (
                    $value,
                ),
            ),
        );

        $dataExpected = array
        (
            $key1 => serialize(
                array(
                    $key2 => serialize(
                        array(
                            $value,
                        )
                    ),
                )
            ),
        );

        $serializedFieldsHelper = new SerializedFieldsHelper();
        $serializedFieldsHelper->setSerializedFields(
            array
            (
                $key1,
                $key1 . $key2,
            )
        );
        $dataSerialized = $serializedFieldsHelper->serializeArray($dataPlain);

        $this->assertEquals($dataExpected, $dataSerialized);
    }

    public function testSerializeFieldWithConfiguredValueReturnsPlainValue()
    {
        $dataPlain = "bar";

        $serializedFieldsHelper = new SerializedFieldsHelper();
        $dataSerialized = $serializedFieldsHelper->serializeField("foo", $dataPlain);

        $this->assertEquals($dataPlain, $dataSerialized);
    }

    public function testSerializeFieldWithConfiguredValueReturnsSerializedArray()
    {
        $dataPlain = array
        (
            "hello",
            "barzahlen",
        );

        $dataExpected = serialize($dataPlain);

        $key = "foobar";

        $serializedFieldsHelper = new SerializedFieldsHelper();
        $serializedFieldsHelper->setSerializedFields(
            array
            (
                $key,
            )
        );

        $dataSerialized = $serializedFieldsHelper->serializeField($key, $dataPlain);

        $this->assertEquals($dataExpected, $dataSerialized);
    }

    public function testUnserializeArrayWithNoConfigReturnsSameData()
    {
        $dataPlain = array
        (
            "hello",
            "barzahlen",
        );

        $serializedFieldsHelper = new SerializedFieldsHelper();
        $dataUnserialized = $serializedFieldsHelper->unserializeArray($dataPlain);

        $this->assertEquals($dataPlain, $dataUnserialized);
    }

    public function testUnserializeArrayWithSerializedDataReturnsUnserialized()
    {
        $key = "hello";
        $value = "barzahlen";

        $dataPlain = array
        (
            $key => array
            (
                $value,
            ),
        );

        $dataSerialized = array
        (
            $key => serialize(
                array
                (
                    $value,
                )
            ),
        );

        $serializedFieldsHelper = new SerializedFieldsHelper();
        $serializedFieldsHelper->setSerializedFields(
            array
            (
                $key,
            )
        );
        $dataUnserialized = $serializedFieldsHelper->unserializeArray($dataSerialized);

        $this->assertEquals($dataPlain, $dataUnserialized);
    }

    public function testUnserializeArrayWithRecursiveSerializedDataReturnsUnserialized()
    {
        $key1 = "hello";
        $key2 = "barzahlen";
        $value = "foobar";

        $dataPlain = array
        (
            $key1 => array
            (
                $key2 => array
                (
                    $value,
                ),
            ),
        );

        $dataSerialized = array
        (
            $key1 => serialize(
                array(
                    $key2 => serialize(
                        array(
                            $value,
                        )
                    ),
                )
            ),
        );

        $serializedFieldsHelper = new SerializedFieldsHelper();
        $serializedFieldsHelper->setSerializedFields(
            array
            (
                $key1,
                $key1 . $key2,
            )
        );
        $dataUnserialized = $serializedFieldsHelper->unserializeArray($dataSerialized);

        $this->assertEquals($dataPlain, $dataUnserialized);
    }

    public function testUnserializeFieldWithNonConfiguredValueReturnsPlainValue()
    {
        $dataPlain = "bar";

        $serializedFieldsHelper = new SerializedFieldsHelper();
        $dataUnserialized = $serializedFieldsHelper->unserializeField("foo", $dataPlain);

        $this->assertEquals($dataPlain, $dataUnserialized);
    }

    public function testUnserializeFieldWithSerializedArrayReturnsUnserializedArray()
    {
        $dataPlain = array
        (
            "hello",
            "barzahlen",
        );

        $dataSerialized = serialize($dataPlain);

        $key = "foobar";

        $serializedFieldsHelper = new SerializedFieldsHelper();
        $serializedFieldsHelper->setSerializedFields(
            array
            (
                $key,
            )
        );

        $dataUnserialized = $serializedFieldsHelper->unserializeField($key, $dataSerialized);

        $this->assertEquals($dataPlain, $dataUnserialized);
    }
}

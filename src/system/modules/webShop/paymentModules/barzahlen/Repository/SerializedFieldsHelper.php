<?php

/**
 * Helps to serialize/unserialize a array with some serialized fields recursive
 */
class SerializedFieldsHelper
{
    /**
     * @var array
     */
    private $serializedFields = array();

    /**
     * Sets config with fields that are serialized values.
     * If fields are recursively serialized, the parent key is the prefix for the child key
     *
     * @param array $serializedFields
     */
    public function setSerializedFields($serializedFields)
    {
        $this->serializedFields = $serializedFields;
    }

    /**
     * Serialize an field with the configuration that setSerializedFields got
     *
     * @param  string $key
     * @param  string|array $value
     * @return string
     */
    public function serializeField($key, $value)
    {
        if (in_array($key, $this->serializedFields)) {
            $serializedArray = $this->serializeArray($value, $key);

            $newValue = serialize($serializedArray);
        } else {
            $newValue = $value;
        }

        return $newValue;
    }

    /**
     * serialize an array with the configuration that setSerializedFields got
     *
     * @param  array $array
     * @param  string $keyPrefix
     * @return array
     */
    public function serializeArray($array, $keyPrefix = "")
    {
        $serializedArray = array();

        foreach ($array as $key => $value) {
            $serializedArray[$key] = $this->serializeField($keyPrefix . $key, $value);
        }

        return $serializedArray;
    }

    /**
     * Unserialize an array with the configuration that setSerializedFields got
     *
     * @param  array $array
     * @param  string $keyPrefix
     * @return array
     */
    public function unserializeArray($array, $keyPrefix = "")
    {
        $unserializedArray = array();

        foreach ($array as $key => $value) {
            $unserializedArray[$key] = $this->unserializeField($keyPrefix . $key, $value);
        }

        return $unserializedArray;
    }

    /**
     * Unserialize an field with the configuration that setSerializedFields got
     *
     * @param  string $key
     * @param  string|array $value
     * @return array
     */
    public function unserializeField($key, $value)
    {
        if (in_array($key, $this->serializedFields)) {
            $unserializedValue = unserialize($value);

            $newValue = $this->unserializeArray($unserializedValue, $key);
        } else {
            $newValue = $value;
        }

        return $newValue;
    }
}

<?php

/**
 * Repository to access the payment module config in the database
 */
class PaymentModuleConfigRepository
{
    /**
     * @var Database
     */
    private $Database;

    /**
     * @param Database $database
     */
    public function __construct($database)
    {
        $this->Database = $database;
    }

    /**
     * Updates an config entry by id
     *
     * @param int $id
     * @param array $config
     */
    public function updateConfigById($id, $config)
    {
        $sql = "UPDATE tl_webshop_paymentmodules SET paymentConfig=? WHERE id=?";

        $configSerialized = serialize($config);

        $this->Database
            ->prepare($sql)
            ->execute($configSerialized, $id);
    }
}

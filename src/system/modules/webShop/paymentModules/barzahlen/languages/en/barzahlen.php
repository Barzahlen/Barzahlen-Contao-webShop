<?php

$GLOBALS['TL_LANG']['webShop']['paymentModules']['barzahlen'] = array
(
    'shopId' => array
    (
        "Shop ID",
        "Your Barzahlen Shop ID",
    ),
    'paymentKey' => array
    (
        "Payment Key",
        "Your Barzahlen Payment Key",
    ),
    'notificationKey' => array
    (
        "Notification Key",
        "Your Barzahlen Notification Key",
    ),
    'sandbox' => array
    (
        "Sandbox",
        "Activate the test mode to process Barzahlen payments via sandbox.",
    ),
    'minTotal' => array
    (
        "Minimum Amount",
        "What is the minimum cart amount to order with Barzahlen? (Max. 1000 EUR)",
    ),
    'maxTotal' => array
    (
        "Maximum Amount",
        "What is the maximum cart amount to order with Barzahlen? (Max. 1000 EUR)",
    ),
    'error_cancellation' => "An error occurred while transmitting your transaction. The order has been cancelled and the administrator has been notified about this. Please try again later.",
    'error_total_to_high' => "Maximum total amount for this payment method is #max_total#",
);

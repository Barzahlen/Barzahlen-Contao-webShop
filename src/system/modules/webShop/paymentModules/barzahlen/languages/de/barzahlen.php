<?php

$GLOBALS['TL_LANG']['webShop']['paymentModules']['barzahlen'] = array
(
    'shopId' => array
    (
        "Shop ID",
        "Ihre Barzahlen Shop ID",
    ),
    'paymentKey' => array
    (
        "Zahlungsschüssel",
        "Ihr Barzahlen Zahlungsschüssel",
    ),
    'notificationKey' => array
    (
        "Benachrichtigungsschlüssel",
        "Ihr Barzahlen Benachrichtigungsschlüssel",
    ),
    'sandbox' => array
    (
        "Testmodus",
        "Aktivieren Sie den Testmodus um Zahlungen über die Sandbox abzuwickeln.",
    ),
    'minTotal' => array
    (
        "Mindestbetrag",
        "Welcher Warenwert muss mindestens erreicht werden, damit Barzahlen als Zahlungsweise angeboten wird? (Max. 1000 EUR)",
    ),
    'maxTotal' => array
    (
        "Höchstbetrag",
        "Welcher Warenwert darf höchstens erreicht werden, damit Barzahlen als Zahlungsweise angeboten wird? (Max. 1000 EUR)",
    ),
    'error_cancellation' => "Während der Übertragung der Transaktion trat ein Fehler auf. Die Bestellung wurde abgebrochen und der Administrator wurde benachrichtigt. Bitte versuchen Sie es später erneut.",
    'error_total_to_high' => "Der maximale Bestellwert für diese Zahlungsart ist #max_total#",
);

<?php

require_once(TL_ROOT . '/system/modules/webShop/paymentModules/barzahlen/include.php');

class barzahlen extends Module
{
    /**
     * @var Config
     */
    private $paymentModuleConfigRepository;
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var PaymentOptionsForm
     */
    private $paymentOptionsForm;
    /**
     * @var BackendConfigForm
     */
    private $backendConfigForm;
    /**
     * @var ValidateTotal
     */
    private $validateTotal;

    /**
     * @var string
     */
    protected $moduleName = 'Barzahlen';
    /**
     * @var array
     */
    protected $config = array();
    /**
     * @var array
     */
    public $data = array();

    public function __construct($arrConfig)
    {
        $this->Import('Database');
        $this->Import('Input');
        $this->Import('Config');
        $this->Import('webShopAddressBook', 'Book');
        $this->Import('webShopShoppingCart', 'Cart');
        $this->Import('webShopShippingController', 'Shipping');
        $this->Import('webShopCouponController', 'Coupons');
        $this->Import('webShopTaxController', 'Tax');

        $serializedFieldsHelper = new SerializedFieldsHelper();

        $this->paymentModuleConfigRepository = new PaymentModuleConfigRepository($this->Database);
        $this->orderRepository = new OrderRepository($this->Database, $serializedFieldsHelper);
        $formFieldFactory = new FormFieldFactory($GLOBALS['BE_FFL']);
        $prepareForWidgetWrapper = new PrepareForWidgetWrapper();
        $this->backendConfigForm = new BackendConfigForm($formFieldFactory, $prepareForWidgetWrapper, $this->Input);
        $this->paymentOptionsForm = new PaymentOptionsForm($this->Input);
        $this->validateTotal = new ValidateTotal($this->getTranslatedStrings());

        if ($this->paymentOptionsForm->isSubmit()) {
            $this->Cart->taxes = $this->Taxes->taxes;
            $this->cartItems = $this->Cart->getItems();
        }

        $this->config = $arrConfig;
    }

    /**
     * Returns module name
     *
     * @return string
     */
    public function moduleInfo()
    {
        return $this->moduleName;
    }

    /**
     * Generates form for configure module in backend
     *
     * @param $arrConfig
     * @return string
     */
    public function generateBEForm($arrConfig)
    {
        if ($this->backendConfigForm->isSubmit()) {
            $configId = $this->backendConfigForm->getConfigId();
            $newConfig = $this->backendConfigForm->getConfig();

            foreach ($newConfig as $key => $value) {
                $newConfig[$key] = trim($value);
            }

            $this->paymentModuleConfigRepository->updateConfigById($configId, $newConfig);
            $config = $newConfig;
        } else {
            $config = deserialize($arrConfig);
            if (!is_array($config)) {
                $config = array();
            }
        }

        $translatedStrings = $this->getTranslatedStrings();

        return $this->backendConfigForm->generateHtml($config, $translatedStrings);
    }

    /**
     * Checks if payment method is valid
     *
     * @return bool
     */
    public function check()
    {
        $valid = true;

        if ($this->paymentOptionsForm->isSubmit()) {
            $this->Shipping->cartItems = $this->cartItems;
            $this->Shipping->select = $this->paymentOptionsForm->getShippingMethod();
            $this->Shipping->country = $this->Book->shippingAddress['country'];

            $calcShipping = new CalculateShipping($GLOBALS['TL_CONFIG']['webShop_pricesBrutto']);
            $calcTotal = new CalculateTotal($calcShipping);

            $total = $calcTotal->calc(
                $this->Cart->brutto,
                $this->Shipping->selectedOption,
                $this->Coupons->couponSum,
                $this->Tax->taxes
            );

            $valid = $this->validateTotal->validate($total, $this->config['minTotal'], $this->config['maxTotal']);
        }

        return $valid;
    }

    /**
     * Returns an error message if payment method is not valid
     *
     * @return string
     */
    public function getError()
    {
        return $this->validateTotal->getErrorMessage();
    }

    /**
     * Will be called when order is finished
     *
     * @return string
     */
    protected function compile()
    {
        $paymentInformation = "";

        if ($this->data['billingValue']) {
            $orderId = $this->Input->get("orderId");
            try {
                $order = $this->orderRepository->getOrderById($orderId);

                $api = new Barzahlen_Api(
                    $this->config['shopId'],
                    $this->config['paymentKey'],
                    $this->config['sandbox']
                );
                $api->setLanguage($GLOBALS['TL_LANGUAGE']);

                $request = new Barzahlen_Request_Payment(
                    $order['email'],
                    $order['paymentAddress']['street'],
                    $order['paymentAddress']['postal'],
                    $order['paymentAddress']['city'],
                    $order['paymentAddress']['country'],
                    $order['billingValue'],
                    "EUR",
                    $order['id']
                );

                $barzahlenPayment = new BarzahlenPayment($this->orderRepository);
                $barzahlenPayment->handlePayment($order, $api, $request);

                $paymentInformation = $barzahlenPayment->getPaymentInfo();
            } catch (Exception $e) {
                $this->log(
                    "Error while transfer order to barzahlen. Chanceled order - " . $e->getMessage(),
                    "barzahlen::compile",
                    TL_ERROR
                );

                $this->orderRepository->markOrderAsCanceled($orderId);
                $translatedStrings = $this->getTranslatedStrings();
                $paymentInformation =
                    "<p class=\"error\">" .
                    $translatedStrings['error_cancellation'] .
                    "</p>";
            }
        }

        return $paymentInformation;
    }

    /**
     * Get strings from language file
     *
     * @return array
     */
    private function getTranslatedStrings()
    {
        $languageDir = TL_ROOT . "/system/modules/webShop/paymentModules/barzahlen/languages/";

        if (is_dir($languageDir . $GLOBALS['TL_LANGUAGE'])) {
            $lang = $GLOBALS['TL_LANGUAGE'];
        } else {
            $lang = 'de';
        }

        require_once(
            $languageDir . $lang . '/barzahlen.php'
        );

        return $GLOBALS['TL_LANG']['webShop']['paymentModules']['barzahlen'];
    }
}

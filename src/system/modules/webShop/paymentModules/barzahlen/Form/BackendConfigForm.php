<?php

/**
 * Creates and access data from config form in backend
 */
class BackendConfigForm
{
    /**
     * Configuration for backend config
     * @var array
     */
    private static $formInputFields = array
    (
        'shopId' => 'text',
        'paymentKey' => 'text',
        'notificationKey' => 'text',
        'sandbox' => 'checkbox',
        'minTotal' => 'text',
        'maxTotal' => 'text',
    );

    /**
     * @var FormFieldFactory
     */
    private $formFieldFactory;
    /**
     * @var PrepareForWidgetWrapper
     */
    private $prepareForWidgetWrapper;

    /**
     * @var Input
     */
    private $input;

    /**
     * @param FormFieldFactory $formFieldFactory ;
     * @param PrepareForWidgetWrapper $prepareForWidgetWrapper
     * @param Input $input
     */
    public function __construct($formFieldFactory, $prepareForWidgetWrapper, $input)
    {
        $this->formFieldFactory = $formFieldFactory;
        $this->prepareForWidgetWrapper = $prepareForWidgetWrapper;
        $this->input = $input;
    }

    /**
     * Generates html form from a config array and an array with strings
     *
     * @param  array $config
     * @param  array $description
     * @return string
     */
    public function generateHtml($config, $description)
    {
        $html = "";

        foreach (self::$formInputFields as $configName => $elementType) {
            $elementConfig = $this->prepareForWidgetWrapper->prepareForWidget(
                array
                (
                    'inputType' => $elementType,
                    'label' => $description[$configName],
                ),
                'paymentConfig[' . $configName . ']'
            );

            $element = $this->formFieldFactory->create($elementType, $elementConfig);

            $element->value = $config[$configName];

            $html .=
                '<h3><label for="ctrl_paymentConfig[' . $configName . ']">' .
                $description[$configName][0] .
                '</label></h3>';
            $html .= $element->generate();
            $html .= '<p style="margin-bottom: 10px;" class="tl_help">' . $description[$configName][1] . '</p>';
        }

        return $html;
    }

    /**
     * Get config id for submitted form
     *
     * @return int
     */
    public function getConfigId()
    {
        return $this->input->get('id');
    }

    /**
     * Get config from submitted form
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->input->post('paymentConfig');
    }

    /**
     * Has form submitted
     *
     * @return bool
     */
    public function isSubmit()
    {
        return $this->input->post('FORM_SUBMIT') == 'tl_webshop_paymentmodules';
    }
}

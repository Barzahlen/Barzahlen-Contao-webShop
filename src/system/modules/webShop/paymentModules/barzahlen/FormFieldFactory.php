<?php

/**
 * Creates input objects depending from mapping
 */
class FormFieldFactory
{
    /**
     * @var array
     */
    private $formFieldMapping;

    /**
     * @param array $formFieldMapping
     */
    public function __construct($formFieldMapping)
    {
        $this->formFieldMapping = $formFieldMapping;
    }

    /**
     * @param  string $formField
     * @param  string $param1
     * @return mixed
     */
    public function create($formField, $param1)
    {
        $elementClass = $this->formFieldMapping[$formField];
        $objElem = new $elementClass($param1);

        return $objElem;
    }
}

<?php

/**
 * Class PrepareForWidgetWrapper
 * Wraps prepareForWidget Method and makes it public. As it is just an Helper function without
 * any dependencies, there is no problem with this, usually dirty, hack
 */
class PrepareForWidgetWrapper extends Frontend
{
    public function __construct()
    {
    }

    /**
     * Convert a back end DCA so it can be used with the widget class
     * @param array
     * @param string
     * @param mixed
     * @param string
     * @param string
     * @return array
     */
    public function prepareForWidget($arrData, $strName, $varValue = null, $strField = '', $strTable = '')
    {
        return parent::prepareForWidget($arrData, $strName, $varValue, $strField, $strTable);
    }
}

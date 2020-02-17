<?php

namespace sequelone\sOnePackage\Properties;


/**
 * Class SimpleProperty
 *
 * A simple property class that does not do any type checking, allowing any sort of value.
 *
 * @package sequelone\Alpaca\Properties
 */
class SimpleProperty extends SnippetProperty {
    /**
     * SimpleProperty constructor. Provide a default value as the first argument.
     * 
     * @param null $default
     */
    public function __construct($default = null)
    {
        $this->default = $default;
        $this->setValue($default);
    }
}

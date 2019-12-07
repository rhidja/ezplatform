<?php

namespace Ram34\CodePostalBundle\FieldType\CodePostal;

use eZ\Publish\Core\FieldType\Value as BaseValue;

/**
 * Class Value
 * @package Ram34\CodePostalTypeBundle\FieldType\CodePostal
 */
class Value extends BaseValue
{
    public $codePostal;

    /**
     * Value constructor.
     * @param array $arg
     */
    public function __construct($arg = [])
    {
        if (!is_array($arg)) {
            $arg = ['codePostal' => $arg];
        }

        parent::__construct($arg);
    }

    /**
     * Methods of the class Value
     * @return string
     */
    public function __toString()
    {
        return (string)$this->codePostal;
    }
}
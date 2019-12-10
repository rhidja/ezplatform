<?php

namespace Ram34\CityFieldTypeBundle\FieldType\City;

use eZ\Publish\Core\FieldType\Value as BaseValue;

class Value extends BaseValue
{
    public $ville;

    public $codePostal;

    public $codeInsee;

    public function __construct(array $values = null)
    {
        foreach ((array)$values as $key => $value) {
            $this->$key = $value;
        }
    }

    public function __toString()
    {
        if (is_array($this->ville)) {
            return implode(', ', $this->ville);
        }

        return (string)$this->ville;
    }
}

<?php

namespace Ram34\CityFieldTypeBundle\FieldType\City;

use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\SPI\FieldType\Value as SPIValue;
use eZ\Publish\Core\FieldType\Value as BaseValue;


class Type extends FieldType
{
    public function getFieldTypeIdentifier()
    {
        return 'ramcity';
    }

    public function getName(SPIValue $value)
    {
        return (string)$value->ville;
    }

    public function getEmptyValue()
    {
        return new Value();
    }

    public function isEmptyValue(SPIValue $value)
    {
        return $value->codePostal === null && $value->codeInsee === null && $value->ville === null;
    }

    protected function createValueFromInput($inputValue)
    {
        if (is_array($inputValue)) {
            $inputValue = new Value($inputValue);
        }

        return $inputValue;
    }

    protected function checkValueStructure(BaseValue $value)
    {
        if (!is_string($value->codePostal)) {
            throw new InvalidArgumentType(
                '$value->codePostal',
                'string',
                $value->codePostal
            );
        }

        if (!is_string($value->codeInsee)) {
            throw new InvalidArgumentType(
                '$value->codeInsee',
                'string',
                $value->codeInsee
            );
        }

        if (!is_string($value->ville)) {
            throw new InvalidArgumentType(
                '$value->ville',
                'string',
                $value->ville
            );
        }
    }

    protected function getSortInfo(BaseValue $value)
    {
        return (string)$value->ville;
    }

    public function fromHash($hash)
    {
        if ($hash === null) {
            return $this->getEmptyValue();
        }

        return new Value($hash);
    }

    public function toHash(SPIValue $value)
    {
        if ($this->isEmptyValue($value)) {
            return null;
        }

        return [
            'codePostal' => $value->codePostal,
            'codeInsee' => $value->codeInsee,
            'ville' => $value->ville,
        ];
    }

    public function isSearchable()
    {
        return true;
    }

    public function toPersistenceValue(SPIValue $value)
    {
        return new FieldValue(
            [
                'data' => null,
                'externalData' => $this->toHash($value),
                'sortKey' => $this->getSortInfo($value),
            ]
        );
    }

    public function fromPersistenceValue(FieldValue $fieldValue)
    {
        if ($fieldValue->externalData === null) {
            return $this->getEmptyValue();
        }

        return $this->fromHash($fieldValue->externalData);
    }
}

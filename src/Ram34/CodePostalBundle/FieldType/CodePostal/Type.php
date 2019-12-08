<?php

namespace Ram34\CodePostalBundle\FieldType\CodePostal;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\SPI\FieldType\Nameable;
use eZ\Publish\Core\FieldType\Value as CoreValue;
use eZ\Publish\SPI\FieldType\Value as SPIValue;
use eZ\Publish\Core\FieldType\ValidationError;
use eZ\Publish\SPI\Persistence\Content\FieldValue as PersistenceValue;

/**
 * Class Type
 * @package Ram34\CodePostalTypeBundle\FieldType\CodePostal
 */
class Type extends FieldType implements Nameable
{
    /**
     * @return string
     */
    public function getFieldTypeIdentifier()
    {
        return 'codepostal';
    }

    /**
     * @param mixed $inputValue
     * @return Value|mixed
     */
    protected function createValueFromInput($inputValue)
    {
        if (is_string($inputValue)) {
            $inputValue = new Value(['codePostal' => $inputValue]);
        }

        return $inputValue;
    }

    /**
     * @param CoreValue $value
     * @throws InvalidArgumentType
     */
    protected function checkValueStructure(CoreValue $value)
    {
        if (!is_string($value->codePostal)) {
            throw new InvalidArgumentType(
                '$value->codePostal',
                'string',
                $value->codePostal
            );
        }
    }

    /**
     * @return SPIValue|Value
     */
    public function getEmptyValue()
    {
        return new Value;
    }

    /**
     * @param FieldDefinition $fieldDefinition
     * @param SPIValue $fieldValue
     * @return array|\eZ\Publish\SPI\FieldType\ValidationError[]
     */
    public function validate(FieldDefinition $fieldDefinition, SPIValue $fieldValue)
    {
        $errors = [];

        if ($this->isEmptyValue($fieldValue)) {
            return $errors;
        }

        // Code Postal validation
        if (!preg_match('#^[0-9]{5}$#', $fieldValue->codePostal)) {
            $errors[] = new ValidationError(
                'Invalid Code Postal value %codePostal%, it should contain 5 digits',
                null,
                ['%codePostal%' => $fieldValue->codePostal]
            );
        }

        return $errors;
    }

    /**
     * @param SPIValue $value
     * @param FieldDefinition $fieldDefinition
     * @param string $languageCode
     * @return string|string[]|null
     */
    public function getFieldName( SPIValue $value , FieldDefinition $fieldDefinition, $languageCode)
    {
        return (string)$value->codePostal;
    }

    /**
     * @param CoreValue $value
     * @return mixed|string
     */
    protected function getSortInfo(CoreValue $value)
    {
        return (string)$value->codePostal;
    }

    /**
     * @param SPIValue $value
     * @return string|void
     */
    public function getName(SPIValue $value)
    {
        throw new \RuntimeException(
            'Name generation provided via NameableField set via "ezpublish.fieldType.nameable" service tag'
        );
    }

    /**
     * @param mixed $hash
     * @return SPIValue|Value
     */
    public function fromHash($hash)
    {
        if ($hash === null) {
            return $this->getEmptyValue();
        }

        return new Value($hash);
    }

    /**
     * @param SPIValue $value
     * @return array|mixed|null
     */
    public function toHash(SPIValue $value)
    {
        if ($this->isEmptyValue($value)) {
            return null;
        }

        return [
            'codePostal' => $value->codePostal,
        ];
    }

    /**
     * @param SPIValue $value
     * @return PersistenceValue
     */
    public function toPersistenceValue(SPIValue $value)
    {
        if ($value === null) {
            return new PersistenceValue(
                [
                    'data' => null,
                    'externalData' => null,
                    'sortKey' => null,
                ]
            );
        }

        return new PersistenceValue(
            [
                'data' => $this->toHash($value),
                'sortKey' => $this->getSortInfo($value),
            ]
        );
    }

    /**
     * @param PersistenceValue $fieldValue
     * @return CoreValue|SPIValue|Value
     */
    public function fromPersistenceValue(PersistenceValue $fieldValue)
    {
        if ($fieldValue->data === null) {
            return $this->getEmptyValue();
        }

        return new Value($fieldValue->data);
    }
}
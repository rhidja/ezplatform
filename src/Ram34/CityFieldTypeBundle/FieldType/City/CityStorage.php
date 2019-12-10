<?php


namespace Ram34\CityFieldTypeBundle\FieldType\City;

use eZ\Publish\SPI\FieldType\GatewayBasedStorage;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;
use eZ\Publish\SPI\Persistence\Content\Field;
use Ram34\CityFieldTypeBundle\FieldType\City\CityStorage\Gateway;

class CityStorage extends GatewayBasedStorage
{
    protected $gateway;

    public function __construct(Gateway $gateway)
    {
        parent::__construct($gateway);
    }

    public function storeFieldData(VersionInfo $versionInfo, Field $field, array $context)
    {
        return $this->gateway->storeFieldData($versionInfo, $field);
    }

    public function getFieldData(VersionInfo $versionInfo, Field $field, array $context)
    {
        $this->gateway->getFieldData($versionInfo, $field);
    }

    public function deleteFieldData(VersionInfo $versionInfo, array $fieldIds, array $context)
    {
        $this->gateway->deleteFieldData($versionInfo, $fieldIds);
    }

    public function hasFieldData()
    {
        return true;
    }

    public function getIndexData(VersionInfo $versionInfo, Field $field, array $context)
    {
        return is_array($field->value->externalData) ? $field->value->externalData['ville'] : null;
    }
}

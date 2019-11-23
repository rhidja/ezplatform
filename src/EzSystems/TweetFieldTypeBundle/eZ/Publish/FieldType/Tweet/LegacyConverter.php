<?php
// TweetFieldTypeBundle/eZ/Publish/FieldType/Tweet/LegacyConverter.php

namespace EzSystems\TweetFieldTypeBundle\eZ\Publish\FieldType\Tweet;

use eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldDefinition;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;

class LegacyConverter implements Converter
{
    /**
     * @param FieldValue $value
     * @param StorageFieldValue $storageFieldValue
     */
    public function toStorageValue(FieldValue $value, StorageFieldValue $storageFieldValue)
    {
        $storageFieldValue->dataText = json_encode($value->data);
        $storageFieldValue->sortKeyString = $value->sortKey;
    }

    /**
     * @param StorageFieldValue $value
     * @param FieldValue $fieldValue
     */
    public function toFieldValue(StorageFieldValue $value, FieldValue $fieldValue)
    {
        $fieldValue->data = json_decode($value->dataText, true);
        $fieldValue->sortKey = $value->sortKeyString;
    }

    /**
     * @param FieldDefinition $fieldDef
     * @param StorageFieldDefinition $storageDef
     */
    public function toStorageFieldDefinition(FieldDefinition $fieldDef, StorageFieldDefinition $storageDef)
    {
    }

    /**
     * @param StorageFieldDefinition $storageDef
     * @param FieldDefinition $fieldDef
     */
    public function toFieldDefinition(StorageFieldDefinition $storageDef, FieldDefinition $fieldDef)
    {
    }

    /**
     * @return string
     */
    public function getIndexColumn(): string
    {
        return 'sort_key_string';
    }
}
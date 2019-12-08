<?php

namespace Ram34\CityFieldTypeBundle\FieldType\City\CityStorage;

use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;
use eZ\Publish\SPI\FieldType\StorageGateway;

abstract class Gateway extends StorageGateway
{
    /**
     * Stores the data stored in the given $field.
     *
     * Potentially rewrites data in $field and returns true, if the $field
     * needs to be updated in the database.
     *
     * @param VersionInfo $versionInfo
     * @param Field $field
     *
     * @return bool If restoring of the internal field data is required
     */
    abstract public function storeFieldData(VersionInfo $versionInfo, Field $field);

    /**
     * Sets the loaded field data into $field->externalData.
     *
     * @param VersionInfo $versionInfo
     * @param Field $field
     *
     * @return array
     */
    abstract public function getFieldData(VersionInfo $versionInfo, Field $field);

    /**
     * Deletes the data for all given $fieldIds.
     *
     * @param VersionInfo $versionInfo
     * @param array $fieldIds
     */
    abstract public function deleteFieldData(VersionInfo $versionInfo, array $fieldIds);
}

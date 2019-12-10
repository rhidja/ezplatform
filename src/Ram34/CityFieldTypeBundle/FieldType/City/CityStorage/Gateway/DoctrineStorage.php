<?php

namespace Ram34\CityFieldTypeBundle\FieldType\City\CityStorage\Gateway;

use Doctrine\DBAL\Connection;
use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;
use PDO;
use Ram34\CityFieldTypeBundle\FieldType\City\CityStorage\Gateway;

class DoctrineStorage extends Gateway
{
    const CITY_TABLE = 'ramcity';

    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function storeFieldData(VersionInfo $versionInfo, Field $field)
    {
        if ($field->value->externalData === null) {
            // Store empty value and return
            $this->deleteFieldData($versionInfo, [$field->id]);
            $field->value->data = [
                'sortKey' => null,
                'hasData' => false,
            ];

            return false;
        }

        if ($this->hasFieldData($field->id, $versionInfo->versionNo)) {
            $this->updateFieldData($versionInfo, $field);
        } else {
            $this->storeNewFieldData($versionInfo, $field);
        }

        $field->value->data = [
            'sortKey' => $field->value->externalData['ville'],
            'hasData' => true,
        ];

        return true;
    }

    protected function updateFieldData(VersionInfo $versionInfo, Field $field)
    {
        $updateQuery = $this->connection->createQueryBuilder();
        $updateQuery->update($this->connection->quoteIdentifier(self::CITY_TABLE))
            ->set($this->connection->quoteIdentifier('code_postal'), ':codePostal')
            ->set($this->connection->quoteIdentifier('code_insee'), ':codeInsee')
            ->set($this->connection->quoteIdentifier('ville'), ':ville')
            ->where(
                $updateQuery->expr()->andX(
                    $updateQuery->expr()->eq(
                        $this->connection->quoteIdentifier('contentobject_attribute_id'),
                        ':fieldId'
                    ),
                    $updateQuery->expr()->eq(
                        $this->connection->quoteIdentifier('contentobject_version'),
                        ':versionNo'
                    )
                )
            )
            ->setParameter(':codePostal', $field->value->externalData['codePostal'])
            ->setParameter(':codeInsee', $field->value->externalData['codeInsee'])
            ->setParameter(':ville', $field->value->externalData['ville'])
            ->setParameter(':fieldId', $field->id, PDO::PARAM_INT)
            ->setParameter(':versionNo', $versionInfo->versionNo, PDO::PARAM_INT)
        ;

        $updateQuery->execute();
    }

    protected function storeNewFieldData(VersionInfo $versionInfo, Field $field)
    {
        $insertQuery = $this->connection->createQueryBuilder();
        $insertQuery
            ->insert($this->connection->quoteIdentifier(self::CITY_TABLE))
            ->values([
                'code_postal' => ':codePostal',
                'code_insee' => ':codeInsee',
                'ville' => ':ville',
                'contentobject_attribute_id' => ':fieldId',
                'contentobject_version' => ':versionNo',
            ])
            ->setParameter(':codePostal', $field->value->externalData['codePostal'])
            ->setParameter(':codeInsee', $field->value->externalData['codeInsee'])
            ->setParameter(':ville', $field->value->externalData['ville'])
            ->setParameter(':fieldId', $field->id)
            ->setParameter(':versionNo', $versionInfo->versionNo)
        ;

        $insertQuery->execute();
    }

    public function getFieldData(VersionInfo $versionInfo, Field $field)
    {
        $field->value->externalData = $this->loadFieldData($field->id, $versionInfo->versionNo);
    }

    protected function loadFieldData($fieldId, $versionNo)
    {
        $selectQuery = $this->connection->createQueryBuilder();
        $selectQuery
            ->select(
                $this->connection->quoteIdentifier('code_postal'),
                $this->connection->quoteIdentifier('code_insee'),
                $this->connection->quoteIdentifier('ville')
            )
            ->from($this->connection->quoteIdentifier(self::CITY_TABLE))
            ->where(
                $selectQuery->expr()->andX(
                    $selectQuery->expr()->eq(
                        $this->connection->quoteIdentifier('contentobject_attribute_id'),
                        ':fieldId'
                    ),
                    $selectQuery->expr()->eq(
                        $this->connection->quoteIdentifier('contentobject_version'),
                        ':versionNo'
                    )
                )
            )
            ->setParameter(':fieldId', $fieldId, PDO::PARAM_INT)
            ->setParameter(':versionNo', $versionNo, PDO::PARAM_INT)
        ;

        $statement = $selectQuery->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!isset($rows[0])) {
            return null;
        }

        return [
            'ville' => $rows[0]['ville'],
            'codeInsee' => $rows[0]['code_insee'],
            'codePostal' => $rows[0]['code_postal'],
        ];
    }

    protected function hasFieldData($fieldId, $versionNo)
    {
        return $this->loadFieldData($fieldId, $versionNo) !== null;
    }

    public function deleteFieldData(VersionInfo $versionInfo, array $fieldIds)
    {
        if (empty($fieldIds)) {
            // Nothing to do
            return;
        }

        $deleteQuery = $this->connection->createQueryBuilder();
        $deleteQuery
            ->delete($this->connection->quoteIdentifier(self::CITY_TABLE))
            ->where(
                $deleteQuery->expr()->andX(
                    $deleteQuery->expr()->in(
                        $this->connection->quoteIdentifier('contentobject_attribute_id'),
                        ':fieldIds'
                    ),
                    $deleteQuery->expr()->eq(
                        $this->connection->quoteIdentifier('contentobject_version'),
                        ':versionNo'
                    )
                )
            )
            ->setParameter(':fieldIds', $fieldIds, Connection::PARAM_INT_ARRAY)
            ->setParameter(':versionNo', $versionInfo->versionNo, PDO::PARAM_INT)
        ;

        $deleteQuery->execute();
    }
}

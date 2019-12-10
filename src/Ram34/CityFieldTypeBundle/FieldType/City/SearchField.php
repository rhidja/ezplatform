<?php

namespace Ram34\CityFieldTypeBundle\FieldType\City;

use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;
use eZ\Publish\SPI\FieldType\Indexable;
use eZ\Publish\SPI\Search;

class SearchField implements Indexable
{
    public function getIndexData(Field $field, FieldDefinition $fieldDefinition)
    {
        return [
            new Search\Field(
                'value_ville',
                $field->value->externalData['ville'],
                new Search\FieldType\StringField()
            ),
            new Search\Field(
                'value_code_postal',
                $field->value->externalData['codePostal'],
                new Search\FieldType\StringField()
            ),
            new Search\Field(
                'value_codeInsee',
                $field->value->externalData['codeInsee'],
                new Search\FieldType\StringField()
            ),
            new Search\Field(
                'fulltext',
                $field->value->externalData['ville'],
                new Search\FieldType\FullTextField()
            ),
        ];
    }

    public function getIndexDefinition()
    {
        return [
            'value_ville' => new Search\FieldType\StringField(),
            'value_code_postal' => new Search\FieldType\StringField(),
            'value_code_insee' => new Search\FieldType\StringField(),
        ];
    }

    public function getDefaultMatchField()
    {
        return 'value_ville';
    }

    public function getDefaultSortField()
    {
        return $this->getDefaultMatchField();
    }
}

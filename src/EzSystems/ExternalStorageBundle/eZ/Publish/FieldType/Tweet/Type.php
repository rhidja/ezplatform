<?php

namespace EzSystems\ExternalStorageBundle\eZ\Publish\FieldType\Tweet;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\SPI\FieldType\Nameable;
use eZ\Publish\Core\FieldType\Value as CoreValue;
use eZ\Publish\SPI\FieldType\Value as SPIValue;
use eZ\Publish\Core\FieldType\ValidationError;
use eZ\Publish\SPI\Persistence\Content\FieldValue as PersistenceValue;
use EzSystems\TweetFieldTypeBundle\Twitter\TwitterClientInterface;

/**
 * Class Type
 * @package EzSystems\ExternalStorageBundle\eZ\Publish\FieldType\Tweet
 */
class Type extends FieldType implements Nameable
{
    /**
     * @var TwitterClientInterface
     */
    protected $twitterClient;

    /**
     * Type constructor.
     * @param TwitterClientInterface $twitterClient
     */
    public function __construct(TwitterClientInterface $twitterClient)
    {
        $this->twitterClient = $twitterClient;
    }

    /**
     * @return string
     */
    public function getFieldTypeIdentifier()
    {
        return 'ramtweet';
    }

    /**
     * @param mixed $inputValue
     * @return Value|mixed
     */
    protected function createValueFromInput($inputValue)
    {
        if (is_string($inputValue)) {
            $inputValue = new Value(['url' => $inputValue]);
        }

        return $inputValue;
    }

    /**
     * @param CoreValue $value
     * @throws InvalidArgumentType
     */
    protected function checkValueStructure(CoreValue $value)
    {
        if (!is_string($value->url)) {
            throw new InvalidArgumentType(
                '$value->url',
                'string',
                $value->url
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

        // Tweet URL validation
        if (!preg_match('#^https?://twitter.com/([^/]+)/status/[0-9]+$#', $fieldValue->url, $m)) {
            $errors[] = new ValidationError(
                'Invalid Twitter status URL %url%',
                null,
                ['%url%' => $fieldValue->url]
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
        return preg_replace(
            '#^https?://twitter\.com/([^/]+)/status/([0-9]+)$#',
            '$1-$2',
            (string)$value->url
        );
    }

    /**
     * @param CoreValue $value
     * @return mixed|string
     */
    protected function getSortInfo(CoreValue $value)
    {
        return (string)$value->url;
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
            'url' => $value->url,
            'authorUrl' => $value->authorUrl,
            'contents' => $value->contents
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
                'externalData' => $this->toHash($value),
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
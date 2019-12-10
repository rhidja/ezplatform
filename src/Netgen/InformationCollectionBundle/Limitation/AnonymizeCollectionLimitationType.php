<?php

declare(strict_types=1);

namespace Netgen\InformationCollectionBundle\Limitation;

use eZ\Publish\API\Repository\Exceptions\NotFoundException as APINotFoundException;
use eZ\Publish\API\Repository\Exceptions\NotImplementedException;
use eZ\Publish\API\Repository\Values\Content\Query\CriterionInterface;
use eZ\Publish\API\Repository\Values\User\Limitation;
use eZ\Publish\API\Repository\Values\ValueObject;
use eZ\Publish\API\Repository\Values\User\UserReference as APIUserReference;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\API\Repository\Values\User\Limitation as APILimitationValue;
use eZ\Publish\Core\Limitation\AbstractPersistenceLimitationType;
use eZ\Publish\SPI\Limitation\Type;
use eZ\Publish\Core\FieldType\ValidationError;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;

class AnonymizeCollectionLimitationType extends AbstractPersistenceLimitationType implements Type
{
    /**
     * {@inheritDoc}
     */
    public function acceptValue(APILimitationValue $limitationValue): void
    {
        if (!$limitationValue instanceof AnonymizeCollectionLimitation) {
            throw new InvalidArgumentType('$limitationValue', 'AnonymizeCollectionLimitation', $limitationValue);
        } elseif (!is_array($limitationValue->limitationValues)) {
            throw new InvalidArgumentType('$limitationValue->limitationValues', 'array', $limitationValue->limitationValues);
        }

        foreach ($limitationValue->limitationValues as $key => $identifier) {
            if (!is_string($identifier)) {
                throw new InvalidArgumentType("\$limitationValue->limitationValues[{$key}]", 'string', $identifier);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function validate(APILimitationValue $limitationValue): array
    {
        $validationErrors = [];
        foreach ($limitationValue->limitationValues as $key => $identifier) {
            try {
                $this->persistence->contentTypeHandler()->loadByIdentifier($identifier);
            } catch (APINotFoundException $e) {
                $validationErrors[] = new ValidationError(
                    "limitationValues[%key%] => '%value%' does not exist in the backend",
                    null,
                    [
                        'value' => $identifier,
                        'key' => $key,
                    ]
                );
            }
        }

        return $validationErrors;
    }

    /**
     * {@inheritDoc}
     */
    public function buildValue(array $limitationValues): Limitation
    {
        return new AnonymizeCollectionLimitation(['limitationValues' => $limitationValues]);
    }

    /**
     * {@inheritDoc}
     */
    public function evaluate(APILimitationValue $value, APIUserReference $currentUser, ValueObject $object, array $targets = null): bool
    {
        if (!$value instanceof AnonymizeCollectionLimitation) {
            throw new InvalidArgumentException('$value', 'Must be of type: AnonymizeCollectionLimitation');
        }

        if (!$object instanceof Content) {
            throw new InvalidArgumentException(
                '$object',
                'Must be of type Content'
            );
        }

        if (empty($value->limitationValues)) {
            return false;
        }

        $contentType = $this->persistence
            ->contentTypeHandler()
            ->load(
                $object->contentInfo->contentTypeId
            );

        return in_array($contentType->identifier, $value->limitationValues);
    }

    /**
     * {@inheritDoc}
     */
    public function getCriterion(APILimitationValue $value, APIUserReference $currentUser): CriterionInterface
    {
        if (empty($value->limitationValues)) {
            throw new \RuntimeException('$value->limitationValues is empty, it should not have been stored in the first place');
        }

        if (!isset($value->limitationValues[1])) {
            // 1 limitation value: EQ operation
            return new Criterion\ContentTypeIdentifier($value->limitationValues[0]);
        }

        // several limitation values: IN operation
        return new Criterion\ContentTypeIdentifier($value->limitationValues);
    }

    /**
     * {@inheritDoc}
     */
    public function valueSchema()
    {
        throw new NotImplementedException(__METHOD__);
    }
}
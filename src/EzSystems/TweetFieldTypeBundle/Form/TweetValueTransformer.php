<?php
namespace EzSystems\TweetFieldTypeBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use eZ\Publish\API\Repository\FieldType;
use eZ\Publish\Core\FieldType\Value;


/**
 * Class TweetValueTransformer
 * @package EzSystems\TweetFieldTypeBundle\Form
 */
class TweetValueTransformer implements DataTransformerInterface
{
    /**
     * @var FieldType
     */
    private $fieldType;

    /**
     * TweetValueTransformer constructor.
     * @param FieldType $fieldType
     */
    public function __construct(FieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    /**
     * @param mixed $value
     * @return mixed|string|null
     */
    public function transform($value)
    {
        if (!$value instanceof Value) {
            return null;
        }

        return (string) $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function reverseTransform($value)
    {
        return $this->fieldType->fromHash($value);
    }
}
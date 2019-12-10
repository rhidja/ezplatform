<?php


namespace Ram34\CityFieldTypeBundle\Form\Transformer;


use Ram34\CityFieldTypeBundle\FieldType\City\Value;
use Symfony\Component\Form\DataTransformerInterface;
use eZ\Publish\API\Repository\FieldType;


/**
 * Class CityValueTransformer
 * @package Ram34\CityFieldTypeBundle\Form\Transformer
 */
class CityValueTransformer implements DataTransformerInterface
{
    /**
     * @var FieldType
     */
    private $fieldType;

    /**
     * CityValueTransformer constructor.
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

        return [
            'ville' => $value->ville,
            'codePostal' => $value->codePostal,
            'codeInsee' => $value->codeInsee,
        ];
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function reverseTransform($value)
    {
        return new Value($value);
    }
}
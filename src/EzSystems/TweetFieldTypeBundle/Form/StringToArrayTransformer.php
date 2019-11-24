<?php

namespace EzSystems\TweetFieldTypeBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * DataTransformer that transforms array into comma-separated string and vice versa
 */
class StringToArrayTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $array
     * @return mixed|string
     */
    public function transform($array)
    {
        if ($array === null) {
            return '';
        }

        return implode(',', $array);
    }

    /**
     * @param mixed $string
     * @return array|mixed
     */
    public function reverseTransform($string)
    {
        if (empty($string)) {
            return [];
        }

        return explode(',', $string);
    }
}
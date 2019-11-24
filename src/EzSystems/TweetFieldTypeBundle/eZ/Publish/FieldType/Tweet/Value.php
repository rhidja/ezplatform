<?php

namespace EzSystems\TweetFieldTypeBundle\eZ\Publish\FieldType\Tweet;

use eZ\Publish\Core\FieldType\Value as BaseValue;

/**
 * Class Value
 * @package EzSystems\TweetFieldTypeBundle\eZ\Publish\FieldType\Tweet
 */
class Value extends BaseValue
{
    /**
     * Tweet URL on twitter.com.
     *
     * @var string
     */
    public $url;

    /**
     * Author's Twitter URL (https://twitter.com/UserName)
     *
     * @var string
     */
    public $authorUrl;

    /**
     * The tweet's embed HTML
     *
     * @var string
     */
    public $contents;

    /**
     * Value constructor.
     * @param array $arg
     */
    public function __construct($arg = [])
    {
        if (!is_array($arg)) {
            $arg = ['url' => $arg];
        }

        parent::__construct($arg);
    }

    /**
     * Methods of the class Value
     * @return string
     */
    public function __toString()
    {
        return (string)$this->url;
    }
}
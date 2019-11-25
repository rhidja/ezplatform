<?php
/**
 * File containing the Twitter Client.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\ExternalStorageBundle\Twitter;

/**
 * Class TwitterClient
 * @package EzSystems\ExternalStorageBundle\Twitter
 */
class TwitterClient implements TwitterClientInterface
{
    /**
     * @param string $statusUrl
     * @return string
     */
    public function getEmbed($statusUrl)
    {
        $parts = explode('/', $statusUrl);
        if (isset($parts[5])) {
            $response = file_get_contents(
                sprintf(
                    'https://api.twitter.com/1/statuses/oembed.json?id=%s&align=center',
                    $parts[5]
                )
            );
            $data = json_decode($response, true);
            return $data['html'];
        }
        return '';
    }

    /**
     * @param $statusUrl
     * @return false|string
     */
    public function getAuthor($statusUrl)
    {
        return substr(
            $statusUrl,
            0,
            strpos($statusUrl, '/status/')
        );
    }
}
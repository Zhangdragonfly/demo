<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */

namespace admin\helpers;

use yii;

/**
 * Class AdminHelper
 * @package admin\helpers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class Post
{
    public static function post($url, $data){
        $postdata = http_build_query(
            $data
        );

        $opts = [
            'http' =>[
                'method'  => 'POST',
                'header'  => [
                    'Content-type: application/x-www-form-urlencoded',
                    'Authorization:Bearer 108353f5bffd92945b9eb3b1b67cddfa',
                ],
                'content' => $postdata,
            ]
        ];
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}
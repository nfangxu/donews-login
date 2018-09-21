<?php
/**
 * Created by PhpStorm.
 * User: nfangxu
 * Date: 2018/9/21
 * Time: 16:36
 */

namespace Fangxu;

class Tools
{
    protected static function config()
    {
        return [
            "key" => env("DONEWS_USER_TOKEN_KEY", "1234567890123456"),
        ];
    }

    public static function getToken($user)
    {
        return static::encode(json_encode($user), static::config()["key"]);
    }

    protected static function encode($data, $key)
    {
        $aes = substr($key, strlen($key) - 16);
        return base64_encode(openssl_encrypt($data, "AES-128-ECB", $aes));
    }

    public static function decode($data)
    {
        $aes = substr(static::config()["key"], strlen(static::config()["key"]) - 16);
        return json_decode(openssl_decrypt(base64_decode($data), "AES-128-ECB", $aes));
    }
}
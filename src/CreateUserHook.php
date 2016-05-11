<?php

namespace XPRS;

class CreateUserHook extends Webhook
{
    public $attributes = [
        'nickname' => '',
        'email'    => '',
        'password' => '', // Encrypted, will need to decrypt,
    ];

    private static function randomString($length = 8)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    public function getParams()
    {
        return [
            'password2'      => $this->attributes['password'] ?: self::randomString(8),
            'email'          => $this->attributes['email'],
            'skipvalidation' => true,
            'noemail'        => true,
        ];
    }

    public function exec(Array $params = [])
    {
        return $this->response = (object) localAPI('addclient', array_merge($this->getParams(), $params), $this->whmcsAdminUser);
    }
}

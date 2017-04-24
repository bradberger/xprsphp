<?php

namespace XPRS;

use WHMCS\User\Client;

class CreateUserHook extends Webhook
{

    public $attributes = [
        'nickname' => '',
        'email'    => '',
        'password' => '', // Encrypted, will need to decrypt,
    ];

    private $existingUser = false;

    private static function randomString($length = 8)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    public function getParams()
    {
        return $this->existingUser ? [
            'clientemail' => $this->attributes['email'],
            'password2'   => $this->attributes['password'] ?: null,
        ] : [
            'password2'      => $this->attributes['password'] ?: self::randomString(8),
            'email'          => $this->attributes['email'],
            'skipvalidation' => true,
            'noemail'        => true,
        ];
    }

    public function exec(Array $params = [])
    {
        $this->existingUser = Client::where('email', $this->attributes['email'])->get()->count() > 0;
        try {
            $user = User::firstOrCreate(['email' => $this->attributes['email']]);
            $user->nickname = $this->attributes['nickname'];
            $user->password = $this->attributes['password'];
            $user->save();
        } catch(Exception $e) {
            logActivity(sprintf('[K2] Error saving xprs user info: %s', $e->getMessage()));
        }

        $params = array_merge($this->getParams(), $params);
        var_dump($params);
        if ($this->existingUser) {
            return $this->response = $this->localAPI('updateclient', $params);
        }
        return $this->response = $this->localAPI('addclient', $params);
    }
}

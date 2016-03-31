<?php

namespace XPRS;

class SuspendUser extends Request
{
    public function __construct($token)
    {
        self::$parent->__construct($token);
    }

    protected function endpoint()
    {
        return '/api/suspend_user';
    }

    protected function requiredFields()
    {
        return ['nickname', 'label', 'api_token'];
    }
}

<?php

namespace XPRS;

class UnsuspendUser extends XPRSRequest
{
    public function __construct($token)
    {
        self::$parent->__construct($token);
    }

    protected function endpoint()
    {
        return '/api/unsuspend_user';
    }

    protected function requiredFields()
    {
        return ['nickname', 'label', 'api_token'];
    }
}

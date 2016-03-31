<?php

namespace XPRS;

class RevokeLicense extends Request
{

    /** @param $vbid string The website id of the license to revoke */
    public $vbid;

    public function __construct($token)
    {
        self::$parent->__construct($token);
    }

    protected function endpoint()
    {
        return '/api/revoke_license';
    }

    protected function requiredFields()
    {
        return ['nickname', 'label', 'api_token', 'vbid'];
    }
}

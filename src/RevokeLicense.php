<?php

namespace XPRS;

class RevokeLicense extends Request
{

    /** @param $vbid string The website id of the license to revoke */
    public $vbid;

    protected function endpoint()
    {
        return '/api/revoke_license';
    }

    protected function requiredFields()
    {
        return ['nickname', 'label', 'vbid'];
    }
}

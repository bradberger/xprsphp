<?php

namespace XPRS;

class UnsuspendUser extends Request
{
    protected function endpoint()
    {
        return '/api/unsuspend_user';
    }

    protected function requiredFields()
    {
        return ['nickname', 'label'];
    }
}

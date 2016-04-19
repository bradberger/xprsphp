<?php

namespace XPRS;

class SuspendUser extends Request
{
    protected function endpoint()
    {
        return '/api/suspend_user';
    }

    protected function requiredFields()
    {
        return ['nickname', 'label'];
    }
}

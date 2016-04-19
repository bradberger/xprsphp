<?php

namespace XPRS;

class CreateUser extends Request
{
    /** @param $email string The new user's email */
    public $email;
    /** @param $password string The new user's password */
    public $password;
    /** @param $phone string The new user's phone */
    public $phone;
    /** @param vbid $send_email If set to true a welcome email will be sent to the user */
    public $send_email;

    protected function endpoint()
    {
        return '/api/create_user';
    }

    protected function requiredFields()
    {
        return ['nickname', 'label', 'email', 'password'];
    }
}

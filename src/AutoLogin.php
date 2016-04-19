<?php

namespace XPRS;

class AutoLogin
{

    private $token;

    public $siteURL;

    public function __construct($token, $siteURL = 'https://xprs.imcreator.com')
    {
        $this->token   = $token;
        $this->siteURL = $siteURL;
    }

    /**
     * Returns the URL to use in for redirecting the client. Does not make the actual
     * HTTP request since that url will automatically redirect the user and likely you want to do that in the browser.
     */
    public function url($nickname, $email, $password)
    {
        return sprintf("%s/api/auto_login_credentials?%s", $this->siteURL, http_build_query([
            'nickname'      => $nickname,
            'email'         => $email,
            'password'      => md5($password),
            'secret_wl_key' => $this->token,
            'api_token'     => $this->token,
        ]));
    }
}

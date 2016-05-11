<?php
namespace XPRS;

class AutoLogin
{
    private $token;
    private $siteURL;
    private $hashPrefix;

    public function __construct($token, $siteURL = 'https://xprs.imcreator.com', $hashPrefix = 'H7x6')
    {
        $this->token      = $token;
        $this->siteURL    = $siteURL;
        $this->hashPrefix = $hashPrefix;
    }

    public function setHashPrefix($prefix)
    {
        $this->hashPrefix = $prefix;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setSiteURL($siteURL)
    {
        $this->siteURL = $siteURL;
    }

    /**
     * Returns the URL to use in for redirecting the client. Does not make the actual
     * HTTP request since that url will automatically redirect the user and likely you want to do that in the browser.
     */
    public function url($label, $nickname, $password)
    {
        return sprintf("%s/api/auto_login_credentials?%s", $this->siteURL, http_build_query([
            'label'         => $label,
            'nickname'      => $nickname,
            'password'      => md5(sprintf('%s%s', $this->hashPrefix, $password)),
            'api_token'     => $this->token,
        ]));
    }

    /**
     * Does an HTTP redirect if the URL is correct and returns null. Otherwise,
     * if there is a return value, it's an error message.
     */
    public function redirect($label, $nickname, $password, $errRedirect = '')
    {
        $url = $this->url($label, $nickname, $password);
        $result = file_get_contents($url);
        if (substr_count($result, "ERROR")) {
            $result = json_decode($result);
            if ($errRedirect) {
                header(sprintf('Location: %s?msg=%s', $errRedirect, $result->MSG));
            }
            return $result->MSG;
        }
        header(sprintf('Location: %s', $url));
        return '';
    }
}

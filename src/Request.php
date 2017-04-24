<?php

namespace XPRS;

abstract class Request
{

    private $baseURL = 'https://xprs.imcreator.com';

    /** The label API token as generated from the white-label dashboard */
    private $token;

    /** The license owner */
    public $nickname;

    /** The label name */
    public $label;

    private $result;

    /** requiredFields must return an array of public properties which are required for the request */
    abstract protected function requiredFields();

    /** endpont must return a string of the API endpoint relative to the base URL */
    abstract protected function endpoint();

    public function __construct($token, array $params = [])
    {
        if (defined('XPRS_BASE_URL')) {
            $this->setBaseURL(XPRS_BASE_URL);
        }
        $this->token = $token;
        foreach ($params as $k => $v) {
            if (property_exists($this, $k)) {
                $this->{$k} = $v;
            }
        }
    }

    public function setBaseURL($url)
    {
        $this->baseURL = $url;
    }

    protected function toArray()
    {
        $skipFields = ['token', 'baseURL'];
        $result = ['api_token' => $this->token];
        $vars = get_object_vars($this);
        $req = $this->requiredFields();
        foreach ($vars as $k => $v) {
            if (in_array($k, $req) || !empty($v)) {
                $result[$k] = $v;
            }
        }

        foreach($skipFields as $k) {
            unset($result[$k]);
        }
        return array_filter($result);
    }

    protected function error()
    {
        foreach ($this->requiredFields() as $key) {
            if (empty($this->{$key})) {
                return sprintf("%s is missing", $key);
            }
        }
    }

    public function send($baseURL = '')
    {
        $fields = $this->toArray();
        $curl = curl_init();
        $url = sprintf('%s?%s', $baseURL ?: $this->getUrl(), http_build_query($fields));
        logActivity(sprintf('[K2] Debug sending to: %s', $url));
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => 0,
        ]);

        $result = curl_exec($curl);
        if ($result === false || curl_errno($curl)) {
            $this->result = new Response(sprintf('{"STATUS":"ERROR","MSG":"%s"}', curl_error($curl)));
            logActivity(sprintf('[K2] Debug: %s', json_encode($this->result)));
            curl_close($curl);
            return $this->result;
        }

        curl_close($curl);
        $this->result = new Response($result);
        logActivity(sprintf('[K2] Debug: %s', json_encode($this->result)));
        return $this->result;
    }

    private function getUrl()
    {
        return sprintf("%s/%s", $this->baseURL, $this->stripPrefix($this->endpoint(), '/'));
    }

    private function stripPrefix($str, $prefix)
    {
        if (substr($str, 0, strlen($prefix)) == $prefix) {
            $str = substr($str, strlen($prefix));
        }
        return $str;
    }
}

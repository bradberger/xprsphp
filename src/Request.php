<?php

namespace XPRS;

abstract class Request
{

    public $base_url = 'https://xprs.imcreator.com';

    /** The label API token as generated from the white-label dashboard */
    public $api_token;

    /** The license owner */
    public $nickname;

    /** The label name */
    public $label;

    private $result;

    /** requiredFields must return an array of public properties which are required for the request */
    abstract protected function requiredFields();

    /** endpont must return a string of the API endpoint relative to the base URL */
    abstract protected function endpoint();

    public function __construct($token)
    {
        $this->api_token = $token;
    }

    protected function toArray()
    {
        $result = [];
        $vars = get_object_vars($this);
        $req = $this->requiredFields();
        foreach ($vars as $k => $v) {
            if (in_array($k, $req) || !empty($v)) {
                $result[$k] = $v;
            }
        }
        return $result;
    }

    protected function error()
    {
        foreach ($this->requiredFields() as $key) {
            if (empty($this->{$key})) {
                return sprintf("%s is missing", $key);
            }
        }
    }

    protected function send()
    {
        $fields = $this->toArray();
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->getUrl(),
            CURLOPT_POST => count($fields),
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $result = curl_exec($curl);
        curl_close($curl);

        if ($result === false || curl_errno($curl)) {
            return $this->result = new Response(sprintf('{"STATUS":"ERROR","MSG":"%s"}', curl_error($curl)));
        }

        return $this->result = new Response($result);
    }

    private function getUrl()
    {
        return sprintf("%s/%s", $this->base_url, $this->stripPrefix($this->endpoint(), '/'));
    }

    private function stripPrefix($str, $prefix)
    {
        if (substr($str, 0, strlen($prefix)) == $prefix) {
            $str = substr($str, strlen($prefix));
        }
        return $str;
    }
}

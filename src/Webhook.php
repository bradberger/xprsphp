<?php

namespace XPRS;

abstract class Webhook
{
    public $attributes = [];
    public $response;

    // exec executes the localAPI call and returns the results, while also setting the results in $response;
    abstract public function exec(Array $params);

    // getParams returns the map of local properties to paramaters which are sent to the WHMCS api via localAPI.
    // It's result is sent to WHMCS via localAPI.
    abstract public function getParams();

    public function __construct(Array $params = [], $whmcsAdminUser = 'apiadmin')
    {
        $this->whmcsAdminUser = $whmcsAdminUser;
        foreach ($params as $k => $v) {
            if (array_key_exists($k, $this->attributes)) {
                $this->attributes[$k] = $v;
            }
        }
        return $this;
    }

    public function result()
    {
        return $this->response;
    }

    public function toJSON()
    {
        return json_encode($this->response);
    }

    public function error()
    {
        if (!$this->response) {
            return null;
        }
        if ($this->response->result === 'error') {
            return $this->response->message;
        }
        return false;
    }

    public function localAPI($cmd, Array $params)
    {
        return (object) localAPI($cmd, $params, $this->whmcsAdminUser);
    }
}

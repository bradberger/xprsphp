<?php

namespace XPRS;

class Response
{
    public $status;
    public $msg;

    private $data;

    public function __construct($str)
    {
        $this->data = $str;
        foreach (json_decode($this->data, true) as $k => $v) {
            if (property_exists($this, strtolower($k))) {
                $this->{strtolower($k)} = $v;
            }
        }
    }

    public function error()
    {
        return strtoupper($this->status) == "ERROR";
    }

    public function message()
    {
        return $this->msg;
    }
}

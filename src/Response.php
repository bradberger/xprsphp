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
            $this->{$k} = $v;
        }
    }

    public function error()
    {
        return strtoupper($this->status) == "ERROR";
    }
}

<?php

namespace XPRS;

use WHMCS\Service\Service as Hosting;

class CheckLiveHook extends Webhook
{
    public $attributes = [
        'vbid'   => '',
        'domain' => '',
    ];

    public function getParams()
    {
        return [
            'domain' => $this->attributes['domain']
        ];
    }

    public function exec(Array $params = [])
    {
        $okay = false;
        $message = 'No such domain';
        $params = array_merge($this->getParams(), $params);
        $records = Hosting::where('domain', $params['domain'])->get();

        if ($records->count() > 0) {
            $status = $records->first()->domainstatus;
            if ($status === 'Active') {
                $okay = true;
            } else {
                $message = sprintf('Domain is %s', strtolower($status));
            }
        }

        return $this->response = (object) [
            'result' => $okay ? 'success' : 'error',
            'message' => $message,
        ];
    }
}

<?php

namespace XPRS;

use WHMCS\User\Client;

class CreateOrderHook extends Webhook
{
    public $attributes = [
        'vbid' => '',
        'user-email' => '',
        'domain' => '',
        'card-amount-int' => '',
        'card-amount-tax' => '',
    ];

    private $orderAttrs = [
        'billingcycle'  => '',
        'clientid'      => null,
        'domaintype'    => '',
        'paymentmethod' => '',
        'pid'           => null,
        'regperiod'     => '',
    ];

    private $optionalOrderAttrs = [
        'eppcode'         => null,
        'nameserver1'     => null,
        'customfields'    => null,
        'configoptions'   => null,
        'promocode'       => null,
        'promooverride'   => null,
        'affid'           => null,
        'noinvoice'       => null,
        'noinvoiceemail'  => null,
        'noemail'         => null,
        'clientip'        => null,
        'addons'          => null,
        'hostname'        => null,
        'ns1prefix'       => null,
        'ns2prefix'       => null,
        'rootpw'          => null,
        'contactid'       => null,
        'dnsmanagement'   => null,
        'domainfields'    => null,
        'emailforwarding' => null,
        'idprotection'    => null,
        'nameserver2'     => null,
        'nameserver3'     => null,
        'nameserver4'     => null,
        'domainrenewals'  => null,
    ];

    public function setOrderAttrs(Array $opts)
    {
        $this->orderAttrs = array_merge($this->orderAttrs, $opts);
    }

    public function setOptionalOrderAttrs(Array $opts)
    {
        $this->optionalOrderAttrs = array_merge($this->optionalOrderAttrs, $opts);
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->orderAttributes['paymentmethod'] = $paymentMethod;
    }

    public function getOrderAttrs() {
        return $this->orderAttrs;
    }

    public function setPID($pid)
    {
        $this->orderAttributes['pid'] = $pid;
    }

    public function getParams()
    {
        $client = Client::where('email', $this->attributes['user-email'])->get();
        if (!$client->count()) {
            throw new Exception('Could not find client');
        }

        $this->orderAttrs['clientid'] = $client->first()->id;
        foreach($this->orderAttrs as $k => $v) {
            if (empty($v)) {
                throw new Exception(sprintf('The order %s attribute is empty', $k));
            }
        }

        return array_merge($this->orderAttrs, array_filter($this->optionalOrderAttrs, function($val) {
            return $val !== null;
        }));
    }

    public function exec(Array $params = [])
    {
        return $this->response = $this->localAPI('addorder', array_merge($this->getParams(), $params));
    }
}

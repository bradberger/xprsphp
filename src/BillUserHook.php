<?php

namespace XPRS;

use WHMCS\User\Client;

class BillUserHook extends Webhook
{

    public $existingUser = true;
    public $attributes = [
        'caller'                 => '', // will always be xprs
        'card-amount-int'        => '', // the amount the user paid
        'card-currency'          => '', // in currency code (i.e. USD)
        'card-cvc'               => '',
        'card-expiry-month'      => '', //
        'card-expiry-year'       => '', //
        'card-holder-first-name' => '', //
        'card-holder-last-name'  => '', //
        'card-number'            => '',
        'card-type'              => '', // (i.e. VISA)
        'coupon-used'            => '',
        'domain'                 => '', //
        'encryptedCreditCard'    => '', // encrypted credit card
        'encryptedCvv'           => '', // encrypted Cvv
        'offer_id'               => '', // The offer id
        'offer_name'             => '', // The name of the offer (i.e. 1-Year subscription)
        'override-currency'      => '',
        'selected-offer-id'      => '',
        'vbid'                   => '', // website vbid,
        'user-email'             => '', //
        'user-country'           => '', // country code (i.e. il for Israel)'
    ];

    private static function randomString($length = 8)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    public function getParams()
    {
        return $this->existingUser ? [
            'clientemail' => $this->attributes['user-email'],
            'firstname'   => $this->attributes['card-holder-first-name'],
            'lastname'    => $this->attributes['card-holder-last-name'],
            'cardnum'     => $this->attributes['card-number'] ?: $this->attributes['encryptedCreditCard'],
            'expdate'     => sprintf('%s%s', $this->attributes['card-expiry-month'], $this->attributes['card-expiry-year']),
            'cardtype'    => $this->attributes['card-type'],
            'country'     => strtoupper(trim($this->attributes['user-country'])),
        ] : [
            'email'          => $this->attributes['user-email'],
            'skipvalidation' => true,
            'password2'      => self::randomString(),
            'firstname'      => $this->attributes['card-holder-first-name'],
            'lastname'       => $this->attributes['card-holder-last-name'],
            'cardnum'        => $this->attributes['card-number'] ?: $this->attributes['encryptedCreditCard'],
            'expdate'        => sprintf('%s%s', $this->attributes['card-expiry-month'], $this->attributes['card-expiry-year']),
            'cardtype'       => $this->attributes['card-type'],
            'country'         => strtoupper(trim($this->attributes['user-country'])),
        ];
    }

    public function exec(Array $params = [])
    {
        $this->existingUser = Client::where('email', $this->attributes['user-email'])->get()->count() > 0;
        if ($this->existingUser) {
            return $this->response = $this->localAPI('updateclient', array_merge($this->getParams(), $params));
        }
        return $this->response = $this->localAPI('addclient', array_merge($this->getParams(), $params));
    }

}

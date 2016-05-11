<?php

namespace XPRS;

class BillUserHook extends Webhook
{

    public $existingUser = true;
    public $attributes = [
        'caller'                 => '', // will always be xprs
        'encryptedCreditCard'    => '', // encrypted credit card
        'encryptedCvv'           => '', // encrypted Cvv
        'card-number'            => '',
        'card-cvc    '           => '',
        'offer_id'               => '', // The offer id
        'card-amount-int'        => '', // the amount the user paid
        'card-holder-first-name' => '', //
        'card-holder-last-name'  => '', //
        'card-currency'          => '', // in currency code (i.e. USD)
        'offer_name'             => '', // The name of the offer (i.e. 1-Year subscription)
        'card-type'              => '', // (i.e. VISA)
        'vbid'                   => '', // website vbid
        'card-expiry-month'      => '', //
        'card-expiry-year'       => '', //
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
        $params = [
            'firstname' => $this->attributes['card-holder-first-name'],
            'lastname'  => $this->attributes['card-holder-last-name'],
            'cardnum'   => $this->attributes['card-number'] ?: $this->attributes['encryptedCreditCard'],
            'expdate'   => sprintf('%s%s', $this->attributes['card-expiry-month'], $this->attributes['card-expiry-year']),
            'cardtype'  => $this->attributes['card-type'],
            'country'   => strtoupper(trim($this->attributes['user-country'])),
        ];

        if (!$this->existingUser) {
            $params = array_merge($params, [
                'email' => $this->attributes['user-email'],
                'skipvalidation' => true,
                'password2' => self::randomString()
            ]);
        } else {
            $params = array_merge($params, ['clientemail' => $this->attributes['user-email']]);
        }

        return $params;
    }

    public function exec(Array $params = [])
    {
        if (!$this->existingUser) {
            return $this->response = (object) localAPI('addclient', array_merge($this->getParams(), $params), $this->whmcsAdminUser);
        }
        $this->response = (object) localAPI('updateclient', array_merge($this->getParams(), $params), $this->whmcsAdminUser);
        if ($this->response->result === 'error' && $this->response->message === 'Client ID Not Found') {
            $this->existingUser = false;
            $this->response = $this->exec($params);
        }
        return $this->response;
    }

}

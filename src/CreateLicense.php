<?php

namespace XPRS;

class CreateLicense extends Request
{

    /** @param string $vbid The website id (if missing, the license will be
      *                     attached to the first website that will be published
      *                     by the user.
      */
    public $vbid;
    /** @param string $domain The website's domain */
    public $domain;
    /** @param boolean $connect_domain If set to true the domain will be added to Heroku */
    public $connect_domain;
    /** Represents a specific subscription (must be unique) */
    public $subscription_id;
    /** @param string $offer_id Represents the selected offer */
    public $offer_id;
    /** @param string $offer_name The selected offer description */
    public $offer_name;

    protected function endpoint()
    {
        return '/api/create_license';
    }

    protected function requiredFields()
    {
        return ['nickname', 'label'];
    }
}

<?php

namespace XPRS;

class User extends \Illuminate\Database\Eloquent\Model
{
    public $timestamps = true;

    protected $table = 'mod_xprs_users';
    protected $primaryKey = 'email';
    protected $fillable = ['email', 'nickname', 'password', 'vbid', 'domain', 'complete'];

    public function getCompleteAttribute($value) {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function setCompleteAttribute($value) {
        $this->attributes['complete'] = (int) filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}

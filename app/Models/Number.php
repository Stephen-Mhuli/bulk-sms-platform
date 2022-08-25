<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    protected $fillable = [
        'number', 'purch_price', 'sell_price', 'from','status',
    ];

    public function admin(){
        return $this->belongsTo(User::class,'admin_id')->withDefault();
    }
    public function customer_numbers(){
        return $this->hasMany(CustomerNumber::class);
    }

    public function requests(){
        return $this->hasOne(NumberRequest::class);
    }
    public function accepted_request(){
        return $this->hasOne(NumberRequest::class)->where('status','accepted');
    }

    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }
}

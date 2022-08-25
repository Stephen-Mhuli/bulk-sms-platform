<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'title', 'price','status','contact_limit','device_limit','daily_receive_limit','daily_send_limit','sms_limit','recurring_type'
    ];
    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }
    public function admin(){
        return $this->belongsTo(User::class,'admin_id')->withDefault();
    }

    public function customer_plans(){
        return $this->hasMany(CustomerPlan::class);
    }

    public function requests(){
        return $this->hasMany(BillingRequest::class);
    }


}

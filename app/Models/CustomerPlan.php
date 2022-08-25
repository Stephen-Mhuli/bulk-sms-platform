<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPlan extends Model
{
    protected $dates = ['renew_date'];
    protected $fillable = [
        'plan_id', 'price','contact_limit','device_limit','daily_receive_limit', 'daily_send_limit','is_current','status','sms_limit','renew_date','recurring_type','email_notified'
    ];

    public function plan(){
        return $this->belongsTo(Plan::class,'plan_id')->withDefault();
    }

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id')->withDefault();
    }
}

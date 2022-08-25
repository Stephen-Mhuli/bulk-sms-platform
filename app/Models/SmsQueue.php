<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsQueue extends Model
{
    use HasFactory;
protected $dates=['schedule_datetime','delivered_at'];

    protected $fillable=['body','message_id','device_unique_id','from','schedule_datetime','to','schedule_completed','campaign_id','message_files','delivered_at','response_code','response_id','status'];

    public function user(){
        return $this->belongsTo(Customer::class,'customer_id')->withDefault();
    }


}

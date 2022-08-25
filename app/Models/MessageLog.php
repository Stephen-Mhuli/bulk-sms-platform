<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageLog extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable=['body','message_id','device_unique_id','queue_id','customer_id','from','type','message_obj','to','schedule_completed','campaign_id','message_files','status','response_code','response_id'];

    public function user(){
        return $this->belongsTo(Customer::class,'customer_id')->withDefault();
    }
}

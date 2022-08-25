<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $dates=['start_date','end_date'];
    protected $fillable=['title', 'start_date', 'end_date', 'start_time', 'end_time', 'status', 'import_fail_message'];

    public function sms_queue(){
        return $this->hasMany(SmsQueue::class);
    }
    public function messages(){
        return $this->hasMany(Message::class);
    }
}

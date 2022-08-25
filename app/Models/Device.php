<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable=['name','model', 'android_version', 'app_version', 'status'];
    public function sent_messages(){
        return $this->hasMany(MessageLog::class, 'from','id')->where('type','sent');
    }
}

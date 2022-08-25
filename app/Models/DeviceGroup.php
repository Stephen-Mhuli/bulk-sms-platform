<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceGroup extends Model
{
    use HasFactory;
    protected $fillable=['name','status'];

    public function device_group_name(){
        return $this->hasMany(DeviceGroupName::class, 'group_id', 'id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id')->withDefault();
    }
}

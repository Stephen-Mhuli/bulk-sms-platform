<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSettings extends Model
{
    protected $fillable=['name','value'];

    public function customer(){
        return $this->belongsTo(Customer::class)->withDefault();
    }
}

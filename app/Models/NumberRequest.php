<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumberRequest extends Model
{
    public function number(){
        return $this->belongsTo(Number::class)->withDefault();
    }
    public function customer(){
        return $this->belongsTo(Customer::class)->withDefault();
    }
}

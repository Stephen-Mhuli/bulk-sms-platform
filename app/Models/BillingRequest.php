<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingRequest extends Model
{
    public function plan(){
        return $this->belongsTo(Plan::class)->withDefault();
    }
    public function customer(){
        return $this->belongsTo(Customer::class)->withDefault();
    }
}

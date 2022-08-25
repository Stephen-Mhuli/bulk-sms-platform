<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNumber extends Model
{
    protected $fillable=['number_id','customer_id','cost','number','forward_to','forward_to_dial_code','expire_date'];
    protected $dates=['expire_date'];
    public function admin_number(){
        return $this->belongsTo(Number::class)->withDefault();
    }

    public function customer(){
        return $this->belongsTo(Customer::class)->withDefault();
    }
}

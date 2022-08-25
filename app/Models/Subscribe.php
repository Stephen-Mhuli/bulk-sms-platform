<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscribe_email'
    ];

    public function customers(){
        return $this->belongsTo(Customers::class);
    }
}

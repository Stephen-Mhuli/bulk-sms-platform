<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable=['name','status'];

    public function contacts(){
        return $this->hasMany(ContactGroup::class)->with('contact');
    }

    public function limited_contacts(){
        return $this->hasMany(ContactGroup::class)->limit(100);
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates=['created_at','updated_at'];

    public function customers(){
        return $this->hasMany(Customer::class,'admin_id');
    }
    public function numbers(){
        return $this->hasMany(Number::class,'admin_id');
    }
    public function number_requests(){
        return $this->hasMany(NumberRequest::class,'admin_id')->where('status','!=','accepted');
    }
    public function active_numbers(){
        return $this->hasMany(Number::class,'admin_id')->whereDoesntHave('requests')->where('numbers.status','active');
    }
    public function available_numbers(){
        return $this->active_numbers()->whereDoesntHave('customer_numbers');
    }

    public function plans(){
        return $this->hasMany(Plan::class,'admin_id');
    }
    public function plan_requests(){
        return $this->hasMany(BillingRequest::class,'admin_id');
    }
    public function active_plans(){
        return $this->plans()->where('status','active');
    }
    public function settings(){
        return $this->hasMany(Settings::class,'admin_id');
    }
    public function pages(){
        return $this->hasMany(Page::class,'admin_id');
    }
    public function tickets(){
        return $this->hasMany(Ticket::class, 'admin_id', 'id');
    }
}

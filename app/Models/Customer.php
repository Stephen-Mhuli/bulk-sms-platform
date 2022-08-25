<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'status', 'email_verified_at',
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

    protected $dates = ['created_at', 'updated_at'];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id')->withDefault();
    }

    public function numbers()
    {
        return $this->hasMany(CustomerNumber::class);
    }

    public function plans()
    {
        return $this->hasMany(CustomerPlan::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderByDesc('created_at');
    }

    public function sent_messages()
    {
        return $this->messages()->where('type', 'sent');
    }

    public function receive_messages()
    {
        return $this->messages()->where('type', 'inbox');
    }

    public function drafts()
    {
        return $this->hasMany(Draft::class)->orderByDesc('created_at');
    }

    public function unread_messages()
    {
        return $this->receive_messages()->where('read', 'no');
    }

    public function settings()
    {
        return $this->hasMany(CustomerSettings::class);
    }
    public function sending_settings()
    {
        return $this->settings()->where('name','sending_settings');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class)->orderByDesc('created_at');
    }

    public function groups()
    {
        return $this->hasMany(Group::class)->orderByDesc('created_at');
    }

    public function active_groups()
    {
        return $this->groups()->where('status', 'active');
    }

    public function sms_queues()
    {
        return $this->hasMany(SmsQueue::class);
    }
    public function campaings()
    {
        return $this->hasMany(Campaign::class);
    }
    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
    public function message_logs()
    {
        return $this->hasMany(MessageLog::class);
    }
    public function subscribes()
    {
        return $this->hasMany(Subscribe::class);
    }
    public function device_groups(){
        return $this->hasMany(DeviceGroup::class, 'customer_id', 'id');
    }

    public function chat_responses(){
        return $this->hasMany(ChatResponse::class, 'customer_id', 'id');
    }

    public function expenses(){
        return $this->hasMany(Expense::class, 'customer_id', 'id');
    }
    public function labels(){
        return $this->hasMany(Label::class,'customer_id', 'id');
    }

    public function exceptions(){
        return $this->hasMany(Exception::class, 'customer_id', 'id');
    }

    public function devices(){
        return $this->hasMany(Device::class, 'customer_id', 'id');
    }

    public function scopeActiveDevices($query,$device_id=null){
        if(!$device_id){
            return $this->devices()->where('status','active');
        }else{
            if(gettype($device_id)=='array'){
                return $this->devices()->where('status','active')->whereIn('id',$device_id);
            }else{
                return $this->devices()->where('status','active')->where('id',$device_id);
            }
        }
    }
    public function authorize_token(){
        return $this->hasOne(AuthorizationToken::class, 'customer_id', 'id');
    }

    public function scopeCurrentPlan(){
        return $this->plans()->latest()->where('is_current','yes')->first();
    }
    public function billing_requests(){
        return $this->hasMany(BillingRequest::class,'customer_id');
    }
}

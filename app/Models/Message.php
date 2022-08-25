<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;
    protected $dates=['schedule_datetime'];

    protected $fillable=['body','numbers','schedule_datetime','type','read','schedule_completed','message_obj','message_files'];

    public function setScheduleDatetimeAttribute($value){
        $this->attributes['schedule_datetime']=Carbon::createFromTimeString($value);
    }

    public function user(){
        return $this->belongsTo(Customer::class,'customer_id')->withDefault();
    }

    public function getTimeAttribute(){
        return $this->created_at->diffForHumans();
    }
    public function getFormattedNumberToAttribute(){

        if(is_array(json_decode($this->numbers)->to)){
            return implode (", ",json_decode($this->numbers)->to);
        }

    }
    public function getFormattedSentFailsAttribute(){
        $numbers=[];
        foreach ($this->sent_fails as $fail){
            $numbers[]=$fail->to_number;
        }
      return implode(', ',$numbers);
    }
    public function getFormattedNumberFromAttribute(){
        return json_decode($this->numbers)->from;
    }

}

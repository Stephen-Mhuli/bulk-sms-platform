<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    protected $dates=['schedule_datetime'];

    protected $fillable=['numbers','body','schedule_datetime'];

    public function setScheduleDatetimeAttribute($value){
        $this->attributes['schedule_datetime']=Carbon::createFromTimeString($value);
    }

    public function getFormattedNumberToAttribute(){
        return implode (", ",isset(json_decode($this->numbers)->to)?json_decode($this->numbers)->to:[]);
    }
    public function getFormattedNumberToArrayAttribute(){
        return isset(json_decode($this->numbers)->to)?json_decode($this->numbers)->to:[];
    }
    public function getFormattedNumberFromAttribute(){
        return json_decode($this->numbers)->from;
    }
}

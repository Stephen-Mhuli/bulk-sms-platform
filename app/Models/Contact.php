<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable=['number','first_name','last_name','email','company','email_notification','address','zip_code','city','state','note','label_id'];

   public function getFullNameAttribute(){

       return trim($this->first_name.' '.$this->last_name);
   }
   public function label(){
       return $this->belongsTo(Label::class, 'label_id', 'id')->withDefault();
   }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectChat extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'directChat';

    public function messages(){
        return $this->hasMany(Message::class,'direct_chat_id');
    }
    public function user1(){
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2(){
        return $this->belongsTo(User::class, 'user2_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectChat extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'directchat';

    protected $fillable = [
        'user1_id',
        'user2_id',
        'dateCreation'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'direct_chat_id');
    }
    public function user1(){
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2(){
        return $this->belongsTo(User::class, 'user2_id');
    }

    public static function betweenUsers($user1_id, $user2_id)
    {
        return self::where(function ($query) use ($user1_id, $user2_id) {
            $query->where('user1_id', $user1_id)
                  ->where('user2_id', $user2_id);
        })->orWhere(function ($query) use ($user1_id, $user2_id) {
            $query->where('user1_id', $user2_id)
                  ->where('user2_id', $user1_id);
        })->first();
    }
    public function getOtherUserAttribute()
    {
        return $this->user1_id == auth()->id() ? $this->user2 : $this->user1;
    }
}

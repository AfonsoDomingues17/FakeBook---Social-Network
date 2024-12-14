<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'content', 'image_url', 'author_id', 'group_id', 'direct_chat_id'
    ];
    public $timestamps = false;
    protected $table = 'message';

    public function author() {
        return $this->belongsTo(User::class,'author_id');
    }
    public function group(){
        return $this->belongsTo(Group::class,'group_id');
    }
    public function directChat(){
        return $this->belongsTo(DirectChat::class, 'direct_chat_id');
    }
    
    public function userTagged(){
        return $this->belongsToMany(Post::class, 'messageTag', 'tagged_user_id', 'message_id');
    }
    
}

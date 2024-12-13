<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nette\Schema\Message;

class Group extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name', 'description', 'photo_url', 'owner_id'
    ];

    public function participants(){
        return $this
        ->belongsToMany(User::class, 'groupparticipant', 'group_id', 'user_id')
        ->withPivot('date_joined');
    }
    public function messages(){
        return $this->hasMany(Message::class,'group_id');
    }

    public function owner(){
        return $this->belongsTo(User::class,'owner_id');
    }

   

}

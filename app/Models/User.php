<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'age', 'bio', 'is_public',
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
    
    public function notifications(){
        return $this->hasMany(Notification::class,'user_id_dest');
    }

    public function posts() {
        return $this->hasMany(Post::class,'owner_id');
    }
    public function taggedPosts(){
    return $this->belongsToMany(Post::class, 'postTag', 'tagged_user_id', 'post_id');
    }
    public function likedPosts(){
        return $this->belongsToMany(Post::class, 'postLikes', 'user_id', 'post_id');
    }
    
    public function following(){
        return $this->belongsToMany(
            User::class,
            'connection',
            'initiator_user_id',
            'target_user_id'
        )->withPivot('createdAt', 'typeR');
    }

    public function followers(){
        return $this->belongsToMany(
            User::class,
            'connection',
            'target_user_id',
            'initiator_user_id'
        )->withPivot('createdAt', 'typeR');
    }

    public function ownesGroups() {
        return $this->hasMany(Group::class,'owner_id');
    }

    public function participantsGroups(){
        return $this
        ->belongsToMany(Group::class, 'groupParticipant', 'user_id', 'group_id')
        ->withPivot('date_joined');
    }

    public function messages() {
        return $this->hasMany(Message::class, 'author_id');
    }

    public function messageTag(){
        return $this->belongsToMany(Message::class, 'messageTag', 'tagged_user_id', 'message_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'author_id');
    }
    public function commentTag(){
        return $this->belongsToMany(Comment::class, 'commentTag', 'tagged_user_id', 'comment_id');
    }

    public function ownesReports()
    {
        return $this->hasMany(Report::class, 'author_id'); 
    }

    public function targetOfReports()
    {
        return $this->hasMany(Report::class, 'target_user_id'); 
    }

    public function directChats(){
    return $this->hasMany(DirectChat::class, 'user1_id')
                ->orWhere('user2_id', $this->id);
    }
    

}

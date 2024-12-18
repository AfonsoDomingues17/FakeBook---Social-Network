<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'comment';
    protected $fillable = ['content', 'author_id', 'post_id', 'is_edited'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    public function reports()
    {
        return $this->hasMany(Report::class); // Uses comment_id by default
    }
    
    public function taggedUsers()
    {
        return $this->belongsToMany(User::class, 'commentTag', 'comment_id', 'tagged_user_id');
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'commentlikes', 'comment_id', 'user_id');
    }

    public function getNumberOfLikes()
    {
        return $this->likedByUsers()->count();
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'post';
    protected $fillable = ['description', 'is_public', 'typep', 'owner_id', 'is_edited'];
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function reports()
    {
        return $this->hasMany(Report::class, 'post_id'); // Specifies the foreign key post_id
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'postcategory', 'post_id', 'category_id');
    }
    public function media()
    {
        return $this->hasMany(Media::class, 'post_id'); // Specifies the foreign key post_id
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id'); 
    }
    public function taggedUsers(){
        return $this->belongsToMany(Post::class, 'posttag', 'post_id','tagged_user_id');
        }
    public function likedByUsers(){
            return $this->belongsToMany(User::class, 'postlikes', 'post_id', 'user_id');
    }

    public function getNumberOfComments()
    {
        return $this->comments()->count();
    }

    public function getNumberOfLikes()
    {
        return $this->likedByUsers()->count();
    }
}

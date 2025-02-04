<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'category';
    
    public function PostCategory(){
        return $this->belongsToMany(Post::class, 'postcategory', 'category_id', 'post_id');
    }
    
}

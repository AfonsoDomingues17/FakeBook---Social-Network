<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLikes extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'postLikes';
    protected $primaryKey = (['post_id', 'user_id']);
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentTag extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'commentTag';
    protected $primaryKey = (['comment_id', 'tagged_user_id']);
}

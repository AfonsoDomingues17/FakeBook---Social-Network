<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'postTag';
    protected $primaryKey = (['post_id', 'tagged_user_id']);
}

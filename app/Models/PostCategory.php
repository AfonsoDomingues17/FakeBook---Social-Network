<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'postcategory';
    protected $primaryKey = (['post_id', 'category_id']);
    public $incrementing = false;
}

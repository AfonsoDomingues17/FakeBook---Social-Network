<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'media';
    protected $fillable = ['photo_url', 'post_id'];
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id'); // Specifies the foreign key post_id
    }
}

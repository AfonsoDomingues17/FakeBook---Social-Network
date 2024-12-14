<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'report';
    protected $fillable = [
        'content',
        'comment_id',
        'post_id',
        'target_user_id',
        'author_id',
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class); // Uses comment_id by default
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id'); // Specifies the foreign key author_id
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id'); // Specifies the foreign key target_user_id
    }
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id'); // Specifies the foreign key post_id
    }
}

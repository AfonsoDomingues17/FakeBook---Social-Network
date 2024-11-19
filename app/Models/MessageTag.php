<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTag extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'messageTag';
    protected $primaryKey = (['message_id', 'tagged_user_id']);
}

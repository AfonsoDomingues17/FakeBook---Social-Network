<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupParticipant extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'groupparticipant';
    protected $fillable = ['group_id', 'user_id', 'date_joined'];
    public $incrementing = false; // Disable auto-incrementing primary key
    protected $primaryKey = ['group_id', 'user_id']; // Define composite primary key
}

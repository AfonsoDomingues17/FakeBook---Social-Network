<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupParticipant extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'groupParticipant';
    protected $primaryKey = (['group_id', 'user_id']);
    protected $fillable = ['group_id', 'user_id', 'date_joined'];
}

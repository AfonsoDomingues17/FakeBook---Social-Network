<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'connection';
    const CREATED_AT = 'createdAt';

    protected $primaryKey = (['initiator_user_id', 'target_user_id']);
    
}

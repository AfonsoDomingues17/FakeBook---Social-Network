<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'notification';
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id_dest'); // Specifies the foreign key user_id_dest
    }
}

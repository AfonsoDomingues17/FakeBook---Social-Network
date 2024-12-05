<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'notification';

    protected $fillable = [
        'content','user_id_dest', 'user_id_src', 'typen', 'is_read'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id_dest'); 
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id_src'); 
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banlist extends Model
{
    use HasFactory;

    protected $table = 'banlist';
    protected $fillable = [
        'user_id',
        'reason',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

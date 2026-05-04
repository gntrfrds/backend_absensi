<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'check_in_photo',
        'check_out_photo'
    ];

    // 🔥 RELASI KE USER
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

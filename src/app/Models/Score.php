<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Memberitahu bahwa skor ini milik satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Memberitahu bahwa skor ini milik satu Topik Materi
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}

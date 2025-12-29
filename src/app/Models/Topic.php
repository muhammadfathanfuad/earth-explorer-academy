<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'content' => 'array', // Otomatis convert JSON <-> Array
    ];
    
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}

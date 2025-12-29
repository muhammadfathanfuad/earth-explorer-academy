<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Definisi Tipe Soal
    const TYPE_MULTIPLE_CHOICE = 'multiple_choice';
    const TYPE_TRUE_FALSE = 'true_false';
    
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}

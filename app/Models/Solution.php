<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercise_id',
        'solution_type',
        'content',
        'path',
        'file_path',
    ];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'module_id',
        'level',
        'instructions',
        'path',
        'image_path',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function solutions()
    {
        return $this->hasMany(Solution::class);
    }
}

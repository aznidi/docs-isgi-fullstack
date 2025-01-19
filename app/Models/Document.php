<?php

namespace App\Models;

use App\Models\User;
use App\Models\Module;
use App\Models\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'codeDoc',
        'nomDoc',
        'libelleDoc',
        'descriptionDoc',
        'type',
        'path',
        'module_id',
        'nbTelechargements',
        'url',
        'likes',         // Nombre de likes
        'reports',       // Nombre de signalements
        'content',
    ];

    /**
     * Relation : Un document appartient à un module.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Relation : Un document peut avoir plusieurs commentaires.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relation : Un document peut avoir plusieurs signalements.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Relation : Un document peut être aimé par plusieurs utilisateurs (Favoris).
     */
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_likes', 'document_id', 'user_id')
                    ->withTimestamps();
    }

     /**
     * Relation : Un document peut être suvgardee par plusieurs utilisateurs (Favoris).
     */

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

}

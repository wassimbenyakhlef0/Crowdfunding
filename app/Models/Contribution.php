<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'montant',
        'message',
        'anonyme',
        'user_id',
        'projet_id',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'anonyme' => 'boolean',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }
}

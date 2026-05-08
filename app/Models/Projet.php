<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'objectif',
        'montant_collecte',
        'date_fin',
        'categorie',
        'image_principale',
        'video_url',
        'user_id',
        'statut',
    ];

    protected $casts = [
        'objectif' => 'decimal:2',
        'montant_collecte' => 'decimal:2',
        'date_fin' => 'date',
    ];

    // Relations
    public function porteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }

    public function updates()
    {
        return $this->hasMany(Update::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    // Accesseurs
    public function getPourcentageCollecteAttribute()
    {
        if ($this->objectif == 0) {
            return 0;
        }
        return round(($this->montant_collecte / $this->objectif) * 100, 2);
    }

    public function getEstTermineAttribute()
    {
        return $this->statut === 'termine' || $this->date_fin < now()->toDateString();
    }

    
    // Scopes
    public function scopePublies($query)
    {
        return $query->where('statut', 'publie');
    }

    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeActifs($query)
    {
        return $query->where('date_fin', '>=', now()->toDateString());
    }

    // Accesseurs supplémentaires
    public function getImageUrlAttribute()
    {
        if ($this->image_principale) {
            return Storage::url($this->image_principale);
        }
        return 'https://via.placeholder.com/600x400?text=Image+du+projet';
    }

    public function getJoursRestantsAttribute()
    {
        $dateFin = \Carbon\Carbon::parse($this->date_fin);
        $aujourdHui = \Carbon\Carbon::today();
        return $aujourdHui->diffInDays($dateFin, false);
    }

    public function getFormateObjectifAttribute()
    {
        return number_format($this->objectif, 2, ',', ' ') . ' €';
    }

    public function getFormateMontantCollecteAttribute()
    {
        return number_format($this->montant_collecte, 2, ',', ' ') . ' €';
    }

    // Génération automatique du slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($projet) {
            if (empty($projet->slug)) {
                $projet->slug = Str::slug($projet->titre) . '-' . uniqid();
            }
        });

        static::updating(function ($projet) {
            if ($projet->isDirty('titre') && empty($projet->slug)) {
                $projet->slug = Str::slug($projet->titre) . '-' . uniqid();
            }
        });
    }
}

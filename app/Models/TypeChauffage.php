<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeChauffage extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom' // Définition des attributs
    ];

    /**
     * Relation One-To-Many : Un type de chauffage peut être associé à plusieurs biens.
     * Cette méthode définit la relation entre le modèle TypeChauffage et le modèle OptionsBien.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function biens()
    {
        return $this->hasMany(OptionsBien::class); // Relation avec la classe OptionsBien
    }
}

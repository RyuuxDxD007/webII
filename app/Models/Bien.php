<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ville;
use App\Models\TypeBien;
use App\Models\TypeAnnonce;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bien extends Model
{
    use HasFactory;

    // Définition des attributs
    protected $fillable = [
        'titre',
        'description',
        'adresse',
        'ville_id',
        'type_bien_id',
        'type_annonce_id',
        'prix',
        'surface',
        'nb_pieces',
        'photo',
        'user_id'
    ];

    /**
     * Relation Many-To-One : Un bien appartient à une ville.
     */
    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    /**
     * Relation Many-To-One : Un bien appartient à un type de bien.
     */
    public function typeBien()
    {
        return $this->belongsTo(TypeBien::class);
    }

    /**
     * Relation Many-To-One : Un bien appartient à un type d'annonce.
     */
    public function typeAnnonce()
    {
        return $this->belongsTo(TypeAnnonce::class);
    }

    /**
     * Relation Many-To-One : Un bien est publié par un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifie si le bien a été posté par l'utilisateur actuellement authentifié.
     *
     * @return bool
     */
    public function isPostedByCurrentUser()
    {
        return $this->user_id === auth()->id();
    }
    
    // Un nouvel accesseur pour obtenir l'URL complète de l'image
    public function getPhotoCompleteAttribute()
    {
        $value = $this->attributes['photo'];
        return $value ? asset('/storage/images/' . $value) : asset('/storage/images/default.jpg');
    }

    /**
     * Génère du HTML coloré pour afficher le type d'annonce (Vente, Location, etc.).
     *
     * @return string
     */
    public function getTypeAnnonceHtmlAttribute()
    {
        $couleur = "red";
        $texteAnnonce = "Vendu/Loué";
        if(!$this->sold)
        {
            switch($this->typeAnnonce->id)
            {
                case 1:
                    $couleur = "green";
                break;
                case 2:
                    $couleur = "orange";
                break;
            }
            $texteAnnonce = $this->typeAnnonce->type_annonce;
        }

        return '<p style="color:'.$couleur.';font-weight:bold">'.$texteAnnonce.'</p>';
    }

    /**
     * Relation One-To-One : Un bien peut avoir une seule configuration d'options.
     */
    public function optionsBien()
    {
    return $this->hasOne(OptionsBien::class, 'bien_id');
    }
}

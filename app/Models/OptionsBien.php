<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsBien extends Model
{
    //Specifie a eloquent le nom de la table a utilisé
    //Sinon eloquent utilise options_biens...
    protected $table = 'options_bien';

    use HasFactory;

    //creation des attributs
    protected $fillable = [
        'bien_id',
        'co2',
        'consomation_energie',
        'type_chauffage_id',
        'double_vitrage',
        'HVAC',
        'solaire',
        'puissance_solaire'
    ];


    /**
     * Relation Many-To-One : Plusieurs optionsBien appartiennent à un type de chauffage.
     * Cette méthode définit la relation entre OptionsBien et TypeChauffage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typeChauffage()
    {
        return $this->belongsTo(TypeChauffage::class); // Relation avec le modèle TypeChauffage
    }

    /**
     * Relation Many-To-One : Plusieurs optionsBien sont liées à un bien.
     * Cette méthode définit la relation entre OptionsBien et Bien.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bien()
    {
        return $this->belongsTo(Bien::class, 'bien_id'); // Relation avec le modèle Bien, via bien_id
    }
}

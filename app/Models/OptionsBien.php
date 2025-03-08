<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsBien extends Model
{
    //Sinon eloquent utilise options_biens...
    protected $table = 'options_bien';

    use HasFactory;


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


    public function typeChauffage()
    {
        return $this->belongsTo(TypeChauffage::class);
    }


    public function bien()
    {
        return $this->belongsTo(Bien::class, 'bien_id');
    }
}

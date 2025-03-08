<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeChauffage extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom'
    ];

    public function biens()
    {
        return $this->hasMany(OptionsBien::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialType extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    /**
     * Relación con las ofertas (offers).
     * Un tipo de material puede tener muchas ofertas.
     */
    public function offers()
    {
        return $this->hasMany(Offer::class, 'tipo_material_id');
    }
}


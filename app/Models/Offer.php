<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'título',
        'descripción',
        'precio',
        'cantidad',
        'tipo_material_id',
        'ubicación',
    ];

    /**
     * Relación con el usuario (user).
     * Una oferta pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el tipo de material (material type).
     * Una oferta pertenece a un tipo de material.
     */
    public function materialType()
    {
        return $this->belongsTo(MaterialType::class, 'tipo_material_id');
    }

    /**
     * Relación con las imágenes (images).
     * Una oferta puede tener muchas imágenes.
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}

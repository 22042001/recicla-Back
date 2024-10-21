<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'ruta_imagen',
    ];

    /**
     * Relación con la oferta (offer).
     * Una imagen pertenece a una oferta.
     */
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}

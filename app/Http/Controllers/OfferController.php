<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    /**
     * Mostrar todas las ofertas.
     */
    public function index()
    {
        $offers = Offer::with('images', 'materialType', 'user')->get();
        return response()->json($offers);
    }

    /**
     * Crear una nueva oferta con imágenes.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'título' => 'required|string|max:255',
            'descripción' => 'required|string',
            'precio' => 'required|numeric',
            'cantidad' => 'required|integer',
            'tipo_material_id' => 'required|exists:material_types,id',
            'ubicación' => 'required|string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $offer = Offer::create([
            'user_id' => Auth::id(),
            'título' => $request->título,
            'descripción' => $request->descripción,
            'precio' => $request->precio,
            'cantidad' => $request->cantidad,
            'tipo_material_id' => $request->tipo_material_id,
            'ubicación' => $request->ubicación,
        ]);

        // Subir imágenes si existen
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('ofertas', 'public');
                Image::create([
                    'offer_id' => $offer->id,
                    'ruta_imagen' => $path,
                ]);
            }
        }

        return response()->json(['message' => 'Oferta creada exitosamente', 'offer' => $offer->load('images')], 201);
    }

    /**
     * Mostrar una oferta específica con imágenes.
     */
    public function show($id)
    {
        $offer = Offer::with('images', 'materialType', 'user')->find($id);

        if (!$offer) {
            return response()->json(['message' => 'Oferta no encontrada'], 404);
        }

        return response()->json($offer);
    }

    /**
     * Actualizar una oferta con imágenes.
     */
    public function update(Request $request, $id)
    {
        $offer = Offer::find($id);

        if (!$offer || $offer->user_id != Auth::id()) {
            return response()->json(['message' => 'Oferta no encontrada o no autorizada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'título' => 'string|max:255',
            'descripción' => 'string',
            'precio' => 'numeric',
            'cantidad' => 'integer',
            'tipo_material_id' => 'exists:material_types,id',
            'ubicación' => 'string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $offer->update($request->only(['título', 'descripción', 'precio', 'cantidad', 'tipo_material_id', 'ubicación']));

        // Actualizar imágenes si existen
        if ($request->hasFile('images')) {
            // Eliminar imágenes anteriores
            $offer->images()->each(function ($image) {
                Storage::disk('public')->delete($image->ruta_imagen);
                $image->delete();
            });

            // Subir nuevas imágenes
            foreach ($request->file('images') as $file) {
                $path = $file->store('ofertas', 'public');
                Image::create([
                    'offer_id' => $offer->id,
                    'ruta_imagen' => $path,
                ]);
            }
        }

        return response()->json(['message' => 'Oferta actualizada exitosamente', 'offer' => $offer->load('images')]);
    }

    /**
     * Eliminar una oferta con sus imágenes.
     */
    public function destroy($id)
    {
        $offer = Offer::find($id);

        if (!$offer || $offer->user_id != Auth::id()) {
            return response()->json(['message' => 'Oferta no encontrada o no autorizada'], 404);
        }

        // Eliminar imágenes asociadas
        $offer->images()->each(function ($image) {
            Storage::disk('public')->delete($image->ruta_imagen);
            $image->delete();
        });

        $offer->delete();

        return response()->json(['message' => 'Oferta eliminada exitosamente']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MaterialType;
use Illuminate\Http\Request;

class MaterialTypeController extends Controller
{
    // Listar todos los tipos de material
    public function index()
    {
        $materials = MaterialType::all();
        return response()->json($materials);
    }

    // Obtener un tipo de material específico
    public function show($id)
    {
        $material = MaterialType::find($id);
        if (!$material) {
            return response()->json(['message' => 'Tipo de material no encontrado'], 404);
        }
        return response()->json($material);
    }

    // Crear un nuevo tipo de material
    public function store(Request $request)
    {
        $material = MaterialType::create($request->only('nombre'));
        return response()->json($material, 201);
    }

    // Actualizar un tipo de material existente
    public function update(Request $request, $id)
    {
        $material = MaterialType::find($id);
        if (!$material) {
            return response()->json(['message' => 'Tipo de material no encontrado'], 404);
        }

        $material->update($request->only('nombre'));

        return response()->json($material);
    }

    // Eliminar un tipo de material
    public function destroy($id)
    {
        $material = MaterialType::find($id);
        if (!$material) {
            return response()->json(['message' => 'Tipo de material no encontrado'], 404);
        }

        $material->delete();

        return response()->json(['message' => 'Tipo de material eliminado']);
    }
}


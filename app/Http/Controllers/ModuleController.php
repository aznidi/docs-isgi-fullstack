<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    // Liste tous les modules
    public function index()
    {
        $modules = Module::all()->map(function ($module) {
            $module->imageMod = $module->imageMod
                ? url("storage/{$module->imageMod}") // Générer l'URL complète
                : null;
            return $module;
        });

        return response()->json($modules);
    }


    // Crée un nouveau module
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codeMod' => 'required|unique:modules|max:255',
            'nomMod' => 'required|max:255',
            'descriptionMod' => 'nullable',
            'anneeMod' => 'required|max:50',
            'imageMod' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
        ]);

        if ($request->hasFile('imageMod')) {
            $validated['imageMod'] = $request->file('imageMod')->store('modules', 'public'); // Stocker l'image
        }

        $module = Module::create($validated);

        return response()->json(['message' => 'Module créé avec succès', 'module' => $module], 201);
    }


    // Récupère un module spécifique
    public function show($id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['message' => 'Module introuvable'], 404);
        }

        return response()->json($module);
    }

    // Met à jour un module
    public function update(Request $request, $id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['message' => 'Module introuvable'], 404);
        }

        $validated = $request->validate([
            'codeMod' => 'sometimes|unique:modules,codeMod,' . $module->id . '|max:255',
            'nomMod' => 'sometimes|max:255',
            'descriptionMod' => 'nullable',
            'anneeMod' => 'max:50',
            'imageMod' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
        ]);

        if ($request->hasFile('imageMod')) {
            // Supprimer l'ancienne image si elle existe
            if ($module->imageMod && Storage::exists("public/{$module->imageMod}")) {
                Storage::delete("public/{$module->imageMod}");
            }
            $validated['imageMod'] = $request->file('imageMod')->store('modules', 'public'); // Stocker la nouvelle image
        }

        $module->update($validated);

        return response()->json(['message' => 'Module mis à jour avec succès', 'module' => $module]);
    }

    // Supprime un module
    public function destroy($id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['message' => 'Module introuvable'], 404);
        }

        $module->delete();

        return response()->json(['message' => 'Module supprimé avec succès']);
    }
}

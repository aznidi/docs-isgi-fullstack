<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Solution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SolutionController extends Controller
{
    // 1. Liste des solutions d'un exercice spécifique
    public function index($exerciseId)
    {
        $exercise = Exercise::with('solutions')->findOrFail($exerciseId);

        return response()->json($exercise->solutions);
    }

    // 2. Ajouter une solution à un exercice
    public function store(Request $request, $exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);

        $request->validate([
            'solution_type' => 'required|in:texte,pdf,vidéo,fichier',
            'content' => 'nullable|string',
            'path' => 'nullable|file|max:10240',
            'file' => 'nullable|file|max:10240',
        ]);

        $path = null;
        $filePath = null;

        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('solutions/paths', 'public');
        }

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('solutions/files', 'public');
        }

        $solution = $exercise->solutions()->create([
            'solution_type' => $request->input('solution_type'),
            'content' => $request->input('content'),
            'path' => $path,
            'file_path' => $filePath,
        ]);

        return response()->json($solution, 201);
    }

    // 3. Mettre à jour une solution
    public function update(Request $request, $id)
    {
        $solution = Solution::findOrFail($id);

        $request->validate([
            'solution_type' => 'sometimes|in:texte,pdf,vidéo,fichier',
            'content' => 'nullable|string',
            'path' => 'nullable|file|max:10240',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('path')) {
            if ($solution->path) {
                Storage::disk('public')->delete($solution->path);
            }
            $solution->path = $request->file('path')->store('solutions/paths', 'public');
        }

        if ($request->hasFile('file')) {
            if ($solution->file_path) {
                Storage::disk('public')->delete($solution->file_path);
            }
            $solution->file_path = $request->file('file')->store('solutions/files', 'public');
        }

        $solution->update($request->only(['solution_type', 'content']));

        return response()->json($solution);
    }

    // 4. Supprimer une solution
    public function destroy($id)
    {
        $solution = Solution::findOrFail($id);

        if ($solution->path) {
            Storage::disk('public')->delete($solution->path);
        }

        if ($solution->file_path) {
            Storage::disk('public')->delete($solution->file_path);
        }

        $solution->delete();

        return response()->json(['message' => 'Solution deleted successfully']);
    }
}

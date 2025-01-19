<?php

namespace App\Http\Controllers\Admin;

use App\Models\Report;
use App\Models\Comment;
use App\Models\Document;
use App\Models\UserLike;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // Liste tous les documents
    public function index(Request $request)
    {
        $documents = Document::with('module')
            ->when($request->search, function ($query, $search) {
                $query->where('nomDoc', 'like', "%{$search}%")
                    ->orWhere('libelleDoc', 'like', "%{$search}%");
            })
            ->paginate(10); // 10 documents par page

        $documents->getCollection()->transform(function ($document) {
            if ($document->type !== 'video') {
                $document->path = $document->path ? url("storage/{$document->path}") : null;
            }
            return $document;
        });

        return response()->json($documents);
    }


    // Ajoute un nouveau document
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codeDoc' => 'required|unique:documents|max:255',
            'nomDoc' => 'required|string|max:255',
            'libelleDoc' => 'required|string',
            'descriptionDoc' => 'required',
            'type' => 'required|in:pdf,video,tp,efm,control,cours,word,txt', // Types autorisés
            'path' => 'required_if:type,pdf,word,txt|file|mimes:pdf,doc,docx,txt|max:2048', // Validation des fichiers pour les types
            'url' => 'required_if:type,video|url', // Validation pour les vidéos
            'module_id' => 'required|exists:modules,id',
            'content' => '',
        ]);

        // Gérer le stockage des fichiers ou l'URL
        if ($request->type === 'video') {
            $validated['path'] = $request->url; // Enregistrer l'URL pour les vidéos
        } elseif ($request->hasFile('path')) {
            $validated['path'] = $request->file('path')->store('documents', 'public'); // Stocker le fichier
        }

        $document = Document::create($validated);

        return response()->json(['message' => 'Document créé avec succès', 'document' => $document], 201);
    }

    // Récupère un document spécifique
    public function show($id)
    {
        $document = Document::with('module')->find($id);

        if (!$document) {
            return response()->json(['message' => 'Document introuvable', 'id' => $id], 404);
        }

        if ($document->type !== 'video' && $document->path) {
            $document->path = url("storage/{$document->path}");
        }

        return response()->json($document);
    }


    // Met à jour un document
    public function update(Request $request, $id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document introuvable'], 404);
        }

        $validated = $request->validate([
            'codeDoc' => 'sometimes|unique:documents,codeDoc,' . $document->id . '|max:255',
            'nomDoc' => 'sometimes|string|max:255',
            'libelleDoc' => 'sometimes',
            'descriptionDoc' => 'sometimes',
            'type' => 'required|in:pdf,video,tp,efm,control,cours,word,txt', // Types autorisés
            'path' => 'required_if:type,pdf,word,txt|file|mimes:pdf,doc,docx,txt|max:2048', // Validation des fichiers pour les types
            'url' => 'required_if:type,video|url', // Validation pour les vidéos
            'module_id' => 'sometimes|exists:modules,id',
            'content' => '',
        ]);

        // Gérer le stockage des fichiers ou l'URL
        if ($request->type === 'video') {
            $validated['path'] = $request->url; // Mettre à jour l'URL
        } elseif ($request->hasFile('path')) {
            // Supprimer l'ancien fichier s'il existe
            if ($document->path && Storage::exists("public/{$document->path}")) {
                Storage::delete("public/{$document->path}");
            }
            $validated['path'] = $request->file('path')->store('documents', 'public'); // Stocker le nouveau fichier
        }

        $document->update($validated);

        return response()->json(['message' => 'Document mis à jour avec succès', 'document' => $document]);
    }

    // Supprime un document
    public function destroy($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document introuvable'], 404);
        }

        // Supprimer le fichier s'il existe et n'est pas une vidéo
        if ($document->type !== 'video' && $document->path && Storage::exists("public/{$document->path}")) {
            Storage::delete("public/{$document->path}");
        }

        $document->delete();

        return response()->json(['message' => 'Document supprimé avec succès']);
    }

    public function searchDocuments(Request $request, $moduleId)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $query = $validated['query'];

        $documents = Document::with('module')
            ->where('module_id', $moduleId)
            ->where(function ($q) use ($query) {
                $q->where('nomDoc', 'like', "%{$query}%")
                ->orWhere('type', 'like', "%{$query}%")
                ->orWhere('libelleDoc', 'like', "%{$query}%")
                ->orWhere('descriptionDoc', 'like', "%{$query}%");
            })
            ->get();

        return response()->json($documents);
    }




    public function addComment(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document introuvable'], 404);
        }

        $comment = Comment::create([
            'document_id' => $id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return response()->json(['message' => 'Commentaire ajouté avec succès', 'comment' => $comment]);
    }

    public function getComments($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document introuvable'], 404);
        }

        $comments = Comment::where('document_id', $id)
            ->with('user:id,name,email,profile_image') // Charger uniquement les champs nécessaires
            ->get();

        return response()->json($comments);
    }

    public function updateComment(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        // Vérifier si l'utilisateur est le propriétaire du commentaire
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $comment->update([
            'content' => $validated['content'],
        ]);

        return response()->json(['message' => 'Commentaire modifié avec succès', 'comment' => $comment]);
    }

    public function deleteComment(Comment $comment)
    {
        // Vérifier si l'utilisateur est le propriétaire du commentaire
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Commentaire supprimé avec succès']);
    }




    public function reportDocument(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document introuvable'], 404);
        }

        $existingReport = Report::where('document_id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReport) {
            return response()->json(['message' => 'Vous avez déjà signalé ce document.'], 400);
        }

        Report::create([
            'document_id' => $id,
            'user_id' => auth()->id(),
            'reason' => $validated['reason'],
        ]);

        return response()->json(['message' => 'Document signalé avec succès']);
    }


    public function getSimilarDocuments($id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json(['message' => 'Document introuvable'], 404);
        }

        $similarDocuments = Document::with('module')
            ->where('module_id', $document->module_id)
            ->where('type', $document->type) // Même type
            ->where('id', '!=', $id) // Exclure le document actuel
            ->take(5) // Limiter à 5 résultats
            ->get();

        return response()->json($similarDocuments);
    }

    // Retourner le total des likes pour un document
    public function getTotalLikes($id)
    {
        $totalLikes = UserLike::where('document_id', $id)->count();

        return response()->json(['likes' => $totalLikes]);
    }



    // Ajouter ou supprimer un like pour l'utilisateur connecté
    public function toggleLike($id)
    {
        $userId = auth()->id();

        // Vérifier si l'utilisateur a déjà liké le document
        $existingLike = UserLike::where('document_id', $id)
            ->where('user_id', $userId)
            ->first();

        // Trouver le document pour incrementer la colonne likes
        $document = Document::find($id);

        if ($existingLike) {
            // Supprimer le like existant
            $existingLike->delete();
            $document->decrement('likes');

            return response()->json(['message' => 'Like supprimé', 'status' => 'removed']);
        } else {
            // Ajouter un nouveau like
            UserLike::create([
                'user_id' => $userId,
                'document_id' => $id,
            ]);
            $document->increment('likes');

            return response()->json(['message' => 'Like ajouté', 'status' => 'added']);
        }
    }


    public function getLikeStatus($id)
    {
        $userId = auth()->id();

        // Vérifier si le document existe
        $document = Document::find($id);
        if (!$document) {
            return response()->json(['message' => 'Document introuvable'], 404);
        }

        // Vérifier si l'utilisateur a aimé le document
        $isLiked = UserLike::where('document_id', $id)
            ->where('user_id', $userId)
            ->exists();

        return response()->json(['liked' => $isLiked]);
    }

    public function filter(Request $request)
    {
        $query = $request->input('query', '');
        $type = $request->input('type', '');
        $date = $request->input('date', '');

        $documents = Document::query();

        if ($query) {
            $documents->where('title', 'like', "%$query%")
                    ->orWhere('description', 'like', "%$query%");
        }

        if ($type) {
            $documents->where('type', $type);
        }

        if ($date) {
            $documents->whereDate('created_at', $date);
        }

        return response()->json($documents->get());
    }



}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return response()->json(['message' => 'Bienvenue sur le tableau de bord admin']);
    }

    public function users()
    {
        $users = User::paginate(10); // Retourner 10 utilisateurs par page
        return response()->json($users, 200);
    }


    public function user($id)
    {
        $user = User::find($id);
        return response()->json($user, 200);
    }


    public function createUser(Request $request)
    {
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());
        return response()->json($user, 200);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé avec succès'], 200);
    }


}

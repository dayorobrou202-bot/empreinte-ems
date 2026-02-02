<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Affiche la liste des stagiaires et le formulaire
     */
    public function index()
    {
        // On récupère les utilisateurs avec leur mentor
        $users = User::with('mentor')->get(); 
        
        // On récupère les mentors potentiels (ceux qui ne sont pas admin)
        $collaborateurs = User::where('role_id', '!=', 1)->get(); 
        
        return view('users.index', compact('users', 'collaborateurs'));
    }

    /**
     * Enregistre le stagiaire avec son mot de passe et son mentor
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6', // Validation du mot de passe
            'mentor_id' => 'required|exists:users,id',
        ]);

        // Le modèle `User` possède le cast `password => 'hashed'`.
        // Il faut donc fournir le mot de passe en clair afin que le cast le hache une seule fois.
        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => $request->password,
            'role_id'   => 2,
            'position'  => 'Stagiaire',
            'mentor_id' => $request->mentor_id,
        ]);

        return back()->with('success', 'Stagiaire créé et assigné avec succès !');
    }

    /**
     * Supprime un stagiaire de la base de données
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Stagiaire supprimé avec succès.');
    }

    /**
     * Réinitialise le mot de passe d'un utilisateur et retourne le mot de passe temporaire.
     * Accessible aux admins uniquement (vérification simple via role_id == 1).
     */
    public function resetPassword(User $user)
    {
        $current = auth()->user();
        if (! $current || ($current->role_id ?? null) !== 1) {
            abort(403);
        }

        $temp = Str::random(8);
        // Utiliser l'attribut pour trigger le cast `password => 'hashed'`
        $user->password = $temp;
        $user->save();

        return back()->with('success', 'Mot de passe réinitialisé.')->with('temp_password', $temp);
    }
}
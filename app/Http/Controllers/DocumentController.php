<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Affiche le formulaire avec la liste des collaborateurs
     */
    public function create()
    {
        // On récupère tous les utilisateurs sauf celui connecté
        $users = User::where('id', '!=', Auth::id())->get();
        
        return view('documents.create', compact('users'));
    }

    /**
     * Enregistre le document et l'envoie au collaborateur
     */
    public function store(Request $request)
    {
        // Validation stricte
        $request->validate([
            'title' => 'required|string|max:255',
            'recipient_id' => 'required|exists:users,id', // Vérifie que l'ID existe en base
            'document' => 'required|file|max:10240', // Max 10Mo
        ]);

        // Sauvegarde du fichier physiquement
        $path = $request->file('document')->store('documents', 'public');

        // Enregistrement dans la base de données
        Document::create([
            'title' => $request->title,
            'file_path' => $path,
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Document transmis avec succès !');
    }
}
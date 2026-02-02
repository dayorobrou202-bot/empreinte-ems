<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        // Récupère les collaborateurs
        $users = User::where('id', '!=', Auth::id())->get();

        // Récupère le dernier message pour l'aperçu à gauche
        foreach ($users as $u) {
            $u->latestMessage = Message::where(function($q) use ($u) {
                $q->where('user_id', Auth::id())->where('receiver_id', $u->id);
            })->orWhere(function($q) use ($u) {
                $q->where('user_id', $u->id)->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'desc')->first();
        }

        $selectedUserId = $request->query('user_id');
        $chatTitle = "Canal Général";
        $messages = collect();

        if ($selectedUserId) {
            // Marque comme lu
            Message::where('user_id', $selectedUserId)
                   ->where('receiver_id', Auth::id())
                   ->update(['is_read' => true]);

            // Historique privé
            $messages = Message::where(function($q) use ($selectedUserId) {
                    $q->where('user_id', Auth::id())->where('receiver_id', $selectedUserId);
                })->orWhere(function($q) use ($selectedUserId) {
                    $q->where('user_id', $selectedUserId)->where('receiver_id', Auth::id());
                })->with('user')->orderBy('created_at', 'asc')->get();
            
            $user = User::find($selectedUserId);
            $chatTitle = $user ? $user->name : "Canal Général";
        } else {
            // Canal Général (Messages de groupe)
            $messages = Message::where('is_group_message', true)
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('pages.messages', compact('users', 'messages', 'chatTitle'));
    }

    public function store(Request $request)
    {
        // 1. Validation : Accepte le texte vide si un fichier est là
        $request->validate([
            'content' => $request->hasFile('document') ? 'nullable|string' : 'required|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:10240',
        ]);

        // 2. CORRECTION SQL : On transforme 0 en null pour le Canal Général
        $receiverId = ($request->receiver_id == 0 || !$request->receiver_id) ? null : $request->receiver_id;

        $data = [
            'user_id'          => Auth::id(),
            'receiver_id'      => $receiverId,
            'content'          => $request->content ?? 'A envoyé un document',
            'is_group_message' => $request->is_group_message,
            'is_read'          => false,
        ];

        // 3. Gestion du fichier (Trombone)
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('uploads', 'public');
            $data['document_path'] = $path;
        }

        Message::create($data);

        return back();
    }
}
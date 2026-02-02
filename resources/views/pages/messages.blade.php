@extends('layouts.dashboard')

@section('content')
<style>
    /* 1. Utilitaires */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* 2. Structure principale responsive */
    .chat-wrapper {
        padding: 15px;
        height: calc(100vh - 120px);
        background: #f8fafc; /* Fond très léger derrière le container */
    }

    .chat-container {
        max-width: 1400px;
        margin: 0 auto;
        height: 100%;
        display: flex;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px; /* Ton arrondi 20px */
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }

    /* 3. Colonnes */
    .sidebar-chat {
        width: 350px;
        border-right: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }

    .main-chat {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }

    /* 4. Bulles de messages */
    .message-bubble {
        max-width: 80%;
        padding: 12px 18px;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.5;
        position: relative;
    }

    .msg-sent {
        background: #2563eb;
        color: white !important;
        border-radius: 18px 18px 4px 18px;
        align-self: flex-end;
    }

    .msg-received {
        background: #f1f5f9;
        color: #1e293b !important;
        border-radius: 18px 18px 18px 4px;
        border: 1px solid #e2e8f0;
        align-self: flex-start;
    }

    /* 5. Responsive Mobile */
    @media (max-width: 768px) {
        .chat-wrapper { padding: 5px; height: calc(100vh - 90px); }
        
        .sidebar-chat { 
            width: 100% !important; 
            display: {{ request('user_id') ? 'none' : 'flex' }}; 
        }
        .main-chat { 
            width: 100% !important; 
            display: {{ request('user_id') ? 'flex' : 'none' }}; 
        }

        .chat-container { border-radius: 15px; border: none; }
    }
</style>

<div class="chat-wrapper">
    <div class="chat-container">
        
        {{-- BARRE LATÉRALE --}}
        <div class="sidebar-chat">
            <div style="padding: 25px; border-bottom: 1px solid #f1f5f9;">
                <h2 style="color: #0f172a; font-weight: 900; text-transform: uppercase; font-size: 14px; letter-spacing: 1px; margin: 0;">Messagerie</h2>
            </div>

            <div class="no-scrollbar" style="flex: 1; overflow-y: auto;">
                {{-- Canal Général --}}
                <a href="{{ route('messages') }}" style="display: block; padding: 18px 25px; text-decoration: none; border-bottom: 1px solid #f8fafc; {{ !request('user_id') ? 'background: #f8fafc; border-left: 4px solid #2563eb;' : '' }}">
                    <div style="font-weight: 800; color: #2563eb; font-size: 11px; text-transform: uppercase;">📢 Canal Général</div>
                    <div style="color: #94a3b8; font-size: 10px; margin-top: 4px; font-weight: 600;">Discussion d'équipe</div>
                </a>

                @foreach($users as $user)
                    <a href="?user_id={{ $user->id }}" style="display: block; padding: 18px 25px; text-decoration: none; border-bottom: 1px solid #f8fafc; transition: 0.2s; {{ request('user_id') == $user->id ? 'background: #f8fafc; border-left: 4px solid #2563eb;' : '' }}">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #1e293b; font-weight: 600; font-size: 14px;">{{ $user->name }}</span>
                            @if($user->latestMessage)
                                <span style="color: #94a3b8; font-size: 10px; font-weight: 600;">{{ $user->latestMessage->created_at->format('H:i') }}</span>
                            @endif
                        </div>
                        <p style="color: #64748b; font-size: 11px; margin: 4px 0 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 400;">
                            {{ $user->latestMessage ? $user->latestMessage->content : 'Aucun message récent' }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ZONE DE DISCUSSION --}}
        <div class="main-chat">
            {{-- En-tête --}}
            <div style="padding: 18px 25px; border-bottom: 1px solid #f1f5f9; background: #fff; display: flex; align-items: center; gap: 15px;">
                {{-- Bouton Retour Mobile --}}
                <a href="{{ route('messages') }}" class="md:hidden" style="text-decoration: none; color: #2563eb; font-size: 20px; font-weight: bold;">
                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h3 style="color: #0f172a; font-weight: 800; text-transform: uppercase; font-size: 13px; margin: 0;">{{ $chatTitle }}</h3>
                    <div style="display: flex; align-items: center; gap: 6px; mt-1">
                        <span style="width: 7px; height: 7px; background: #10b981; border-radius: 50%;"></span>
                        <span style="font-size: 9px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">En ligne</span>
                    </div>
                </div>
            </div>

            {{-- Chat Box --}}
            <div id="chat-box" class="no-scrollbar" style="flex: 1; padding: 25px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; background: #fdfdfd;">
                @foreach($messages as $msg)
                    <div style="display: flex; {{ $msg->user_id == auth()->id() ? 'justify-content: flex-end;' : 'justify-content: flex-start;' }}">
                        <div class="message-bubble {{ $msg->user_id == auth()->id() ? 'msg-sent' : 'msg-received' }}">
                            @if(!request('user_id') && $msg->user_id != auth()->id())
                                <div style="font-size: 10px; font-weight: 800; color: #2563eb; text-transform: uppercase; margin-bottom: 6px;">{{ $msg->user->name }}</div>
                            @endif
                            
                            <div style="word-wrap: break-word;">{{ $msg->content }}</div>
                            
                            @if($msg->document_path)
                                <div style="margin-top: 10px; padding: 10px; background: rgba(0,0,0,0.05); border-radius: 10px; border: 1px solid rgba(0,0,0,0.05);">
                                    <a href="{{ asset('storage/' . $msg->document_path) }}" target="_blank" style="color: inherit; font-size: 10px; font-weight: 800; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                                        📎 DOCUMENT JOINT
                                    </a>
                                </div>
                            @endif

                            <div style="font-size: 9px; opacity: 0.7; margin-top: 6px; text-align: right; font-weight: 600;">
                                {{ $msg->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Zone de Saisie (Texte Noir Forcé) --}}
            <div style="padding: 20px 25px; background: #fff; border-top: 1px solid #f1f5f9;">
                <form action="{{ route('messages.store') }}" method="POST" style="display: flex; gap: 12px; align-items: center;">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ request('user_id', 0) }}">
                    <input type="hidden" name="is_group_message" value="{{ request('user_id') ? '0' : '1' }}">

                    {{-- Ici le color: #000000 !important règle ton problème de visibilité --}}
                    <input type="text" name="content" required autocomplete="off" placeholder="Écrire un message..." 
                           style="flex: 1; padding: 14px 22px; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 30px; font-size: 14px; outline: none; font-weight: 600; color: #000000 !important;">

                    <button type="submit" style="background: #2563eb; color: white; border: none; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);">
                        <svg style="width: 20px; height: 20px; fill: white;" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Scroll automatique vers le bas
    const box = document.getElementById('chat-box');
    box.scrollTop = box.scrollHeight;
</script>
@endsection
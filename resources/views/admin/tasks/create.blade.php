@extends('layouts.dashboard')

@section('inner-content')
<div class="space-y-8 admin-task-form" style="padding: 40px; font-family: 'Inter', sans-serif; background-color: #ffffff;">
    <style>
        .admin-task-form input[type="text"],
        .admin-task-form input[type="date"],
        .admin-task-form textarea {
            color: #0f172a !important;
            background-color: #ffffff !important;
            caret-color: #0f172a !important;
            -webkit-text-fill-color: #0f172a !important;
        }

        .admin-task-form ::placeholder {
            color: #94a3b8 !important;
        }

        .admin-task-form input:focus,
        .admin-task-form textarea:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
    
    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 50px; justify-content: center;">
        <div style="width: 6px; height: 50px; background-color: #2563eb; border-radius: 10px;"></div>
        <h1 style="font-size: 48px; font-weight: 900; text-transform: uppercase; color: #0f172a; letter-spacing: -2px; margin: 0;">
            Assigner une <span style="color: #2563eb;">Mission</span>
        </h1>
    </div>

    <form action="{{ route('admin.tasks.store') }}" method="POST" style="max-width: 1000px; margin: 0 auto;">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div style="background-color: #f8fafc; padding: 30px; border-bottom: 4px solid #2563eb; border-left: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-top: 1px solid #e2e8f0;">
                <label style="color: #64748b; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 15px;">Collaborateur</label>
                <div style="color: #0f172a; font-size: 28px; font-weight: 900; text-transform: uppercase;">{{ $user->name }}</div>
            </div>

            <div style="background-color: #f8fafc; padding: 30px; border-bottom: 4px solid #2563eb; border-left: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-top: 1px solid #e2e8f0;">
                <label style="color: #64748b; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 15px;">Date d'échéance</label>
                <input type="date" name="due_date" required 
                    style="background: transparent; border: none; color: #0f172a; font-size: 24px; font-weight: 900; width: 100%; outline: none; cursor: pointer;">
            </div>
        </div>

        <div style="background-color: #f8fafc; padding: 30px; border-bottom: 4px solid #2563eb; border-left: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-top: 1px solid #e2e8f0; margin-bottom: 20px;">
            <label style="color: #64748b; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 15px;">Intitulé de la mission</label>
            <input type="text" name="title" required placeholder="EX: ANALYSER LES RAPPORTS..."
                style="background: transparent; border: none; color: #0f172a; font-size: 24px; font-weight: 900; width: 100%; outline: none; text-transform: uppercase;">
        </div>

        <div style="background-color: #f8fafc; padding: 30px; margin-bottom: 40px; border: 1px solid #e2e8f0;">
            <label style="color: #64748b; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 15px;">Détails / Instructions</label>
            <textarea name="description" rows="4" placeholder="PRÉCISEZ ICI..."
                style="background: transparent; border: none; color: #475569; font-size: 18px; font-weight: 700; width: 100%; outline: none; resize: none;"></textarea>
        </div>

        <div style="text-align: center;">
            <button type="submit" style="background: none; border: 2px solid #2563eb; color: #2563eb; font-weight: 900; text-transform: uppercase; font-size: 16px; letter-spacing: 4px; cursor: pointer; padding: 15px 40px; transition: all 0.3s ease;">
                Envoyer la mission maintenant
            </button>
        </div>
    </form>
</div>
@endsection
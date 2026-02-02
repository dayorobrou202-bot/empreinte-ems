<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\FormHistory;
use App\Models\User;
use App\Notifications\NewPayrollAvailable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class PaieController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('payrolls')) {
            return view('pages.paie', ['payrolls' => collect()])
                ->withErrors(['migration' => 'Le module de paie n\'est pas encore initialisé.']);
        }

        // CORRECTION ICI : Redirige vers le nom exact défini dans web.php
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.paie.create');
        }

        try {
            $payrolls = Payroll::where('user_id', auth()->id())
                ->orderBy('month', 'desc')
                ->get();

            return view('pages.paie', compact('payrolls'));
        } catch (QueryException $e) {
            Log::error('PaieController@index: ' . $e->getMessage());
            return view('pages.paie', ['payrolls' => collect()]);
        }
    }

    public function create()
    {
        $users = User::where('role_id', '!=', 1)->orderBy('name')->get();
        return view('pages.admin.paie', compact('users'));
    }

    public function adminStore(Request $request)
    {
        // Normalisation du montant
        $rawAmount = (string) $request->input('amount', '');
        $normalized = trim(preg_replace('/[\s\u{00A0}]+/u', '', $rawAmount));
        $normalized = preg_replace('/[A-Za-z]+/u', '', $normalized);
        if (strpos($normalized, ',') !== false && strpos($normalized, '.') === false) {
            $normalized = str_replace(',', '.', $normalized);
        } else {
            $normalized = str_replace(',', '', $normalized);
        }
        $normalized = preg_replace('/[^0-9.\-]+/', '', $normalized);
        $request->merge(['amount' => $normalized]);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month'   => 'required|string',
            'amount'  => 'required|numeric|min:0',
            'pdf'     => 'nullable|file|mimes:pdf|max:5000',
        ]);

        try {
            $path = null;
            if ($request->hasFile('pdf')) {
                $path = $request->file('pdf')->store('bulletins_paie', 'public');
            }

            $payroll = Payroll::create([
                'user_id'  => $request->user_id,
                'month'    => $request->month,
                'amount'   => $request->amount,
                'pdf_path' => $path,
                'status'   => 'paid',
            ]);

            return redirect()->back()->with('success', 'Fiche de paie enregistrée.');
        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['db' => "Erreur : table 'payrolls' absente."]);
        }
    }
}
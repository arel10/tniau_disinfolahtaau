<?php

namespace App\Http\Controllers;

use App\Models\Kontak;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    /**
     * Display the kontak form.
     */
    public function index()
    {
        return view('public.kontak.index');
    }

    /**
     * Store a newly created kontak message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string|min:10',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'subjek.required' => 'Subjek harus diisi.',
            'pesan.required' => 'Pesan harus diisi.',
            'pesan.min' => 'Pesan minimal 10 karakter.',
        ]);

        Kontak::create($validated);

        return redirect()
            ->back()
            ->with('success', __('messages.flash_contact_sent'));
    }
}

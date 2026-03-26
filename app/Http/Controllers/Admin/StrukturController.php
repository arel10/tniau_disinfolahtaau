<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Struktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StrukturController extends Controller
{
    /**
     * Display the org chart view for editing.
     */
    public function index(Request $request)
    {
        $strukturs = Struktur::where('is_active', true)
            ->orderBy('urutan')
            ->get()
            ->keyBy('kode');

        // Prepare JSON data for JavaScript (avoids Blade @json parse issues)
        $strukturJson = $strukturs->map(function($s) {
            return [
                'id' => $s->id,
                'kode' => $s->kode,
                'nama_jabatan' => $s->nama_jabatan,
                'nama_lengkap_jabatan' => $s->nama_lengkap_jabatan,
                'unit' => $s->unit,
                'nama_pejabat' => $s->nama_pejabat,
                'pangkat' => $s->pangkat,
                'nrp' => $s->nrp,
                'tanggal_lahir' => $s->tanggal_lahir ? $s->tanggal_lahir->format('Y-m-d') : '',
                'foto' => $s->foto ? asset('storage/' . $s->foto) : asset('assets/image/default-profile.svg'),
            ];
        });

        $updateUrls = $strukturs->map(function($s) {
            return route('admin.struktur.update', $s);
        });

        return view('admin.struktur.index', compact('strukturs', 'strukturJson', 'updateUrls'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Struktur $struktur)
    {
        $parents = Struktur::where('id', '!=', $struktur->id)->orderBy('urutan')->get();
        return view('admin.struktur.edit', compact('struktur', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Struktur $struktur)
    {
        $validated = $request->validate([
            'nama_pejabat' => 'nullable|string|max:255',
            'nrp' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($struktur->foto) {
                Storage::disk('public')->delete($struktur->foto);
            }
            $validated['foto'] = $request->file('foto')->store('struktur', 'public');
        }

        $struktur->update($validated);

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan!',
                'data' => [
                    'nama_pejabat' => $struktur->nama_pejabat,
                    'foto' => $struktur->foto ? asset('storage/' . $struktur->foto) : asset('assets/image/default-profile.svg'),
                ]
            ]);
        }

        return redirect()->route('admin.struktur.index')
            ->with('success', __('messages.flash_updated', ['item' => __('messages.admin_struktur_organisasi')]));
    }
}

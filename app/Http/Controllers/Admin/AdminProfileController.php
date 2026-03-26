<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil admin.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Simpan perubahan profil admin.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email'    => 'required|email|max:100|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20',
        ];

        $messages = [
            'username.required' => 'Username harus diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'email.required'    => 'Email harus diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah digunakan.',
        ];

        // Validasi password hanya jika diisi
        $changingPassword = $request->filled('password_baru');
        if ($changingPassword) {
            $rules['password_lama']            = 'required|string';
            $rules['password_baru']            = ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()->symbols()];
            $rules['password_baru_confirmation'] = 'required';

            $messages['password_lama.required']            = 'Password lama harus diisi.';
            $messages['password_baru.required']            = 'Password baru harus diisi.';
            $messages['password_baru.min']                 = 'Password baru minimal 8 karakter.';
            $messages['password_baru.confirmed']           = 'Konfirmasi password baru tidak cocok.';
        }

        $request->validate($rules, $messages);

        // Cek password lama jika hendak ganti password
        if ($changingPassword) {
            if (!Hash::check($request->password_lama, $user->password)) {
                return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.'])->withInput();
            }
        }

        // Update data
        $data = [
            'username' => $request->username,
            'email'    => $request->email,
            'phone'    => $request->phone,
        ];

        if ($changingPassword) {
            $data['password'] = bcrypt($request->password_baru);
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}

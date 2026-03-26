<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppOtpSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class ForgotPasswordOtpController extends Controller
{
    // -------------------------------------------------------
    // STEP 1 : Tampilkan form input username
    // -------------------------------------------------------
    public function showRequestForm()
    {
        return view('auth.forgot-otp-request');
    }

    // -------------------------------------------------------
    // STEP 2 : Kirim OTP ke email / whatsapp
    // -------------------------------------------------------
    public function sendOtp(Request $request)
    {
        $request->validate([
            'username' => 'required|string|exists:users,username',
            'channel'  => 'required|in:email,whatsapp',
        ], [
            'username.required' => 'Username harus diisi.',
            'username.exists'   => 'Username tidak ditemukan.',
            'channel.required'  => 'Pilih metode pengiriman OTP.',
            'channel.in'        => 'Metode tidak valid.',
        ]);

        $user = User::where('username', $request->username)->firstOrFail();

        // Cek apakah masih dalam masa blokir (otp_attempts >= 5 & belum expired + 10 menit)
        if ($user->otp_attempts >= 5 && $user->otp_expires_at && now()->lt($user->otp_expires_at)) {
            $waitMinutes = now()->diffInMinutes($user->otp_expires_at, false);
            return back()->withErrors(['username' => "Terlalu banyak percobaan. Coba lagi dalam {$waitMinutes} menit."])
                         ->withInput();
        }

        // Generate OTP
        $otp      = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiry   = now()->addMinutes(10);
        $message  = "Kode OTP reset password Anda: {$otp}. Berlaku 10 menit.";

        // Simpan ke DB
        $user->update([
            'otp_code'        => $otp,
            'otp_expires_at'  => $expiry,
            'otp_channel'     => $request->channel,
            'otp_attempts'    => 0,
        ]);

        // Kirim OTP
        if ($request->channel === 'email') {
            try {
                Mail::raw($message, function ($m) use ($user) {
                    $m->to($user->email)->subject('[TNI AU] Kode OTP Reset Password');
                });
            } catch (\Exception $e) {
                Log::error('[OTP Email Error] ' . $e->getMessage());
            }
        } else {
            // WhatsApp (stub - log only)
            $phone = $user->phone ?? '-';
            app(WhatsAppOtpSender::class)->send($phone, $message);
        }

        return redirect()->route('password.otp.verify.form', ['username' => $user->username])
                         ->with('info', 'Kode OTP telah dikirim. Periksa ' . ($request->channel === 'email' ? 'email' : 'WhatsApp') . ' Anda.');
    }

    // -------------------------------------------------------
    // STEP 3 : Tampilkan form verifikasi OTP + password baru
    // -------------------------------------------------------
    public function showVerifyForm(Request $request)
    {
        $username = $request->query('username');
        if (!$username) {
            return redirect()->route('password.otp.request');
        }
        return view('auth.forgot-otp-verify', compact('username'));
    }

    // -------------------------------------------------------
    // STEP 4 : Verifikasi OTP & reset password
    // -------------------------------------------------------
    public function verifyAndReset(Request $request)
    {
        $request->validate([
            'username'              => 'required|string|exists:users,username',
            'otp'                   => 'required|digits:6',
            'password'              => ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
            'password_confirmation' => 'required',
        ], [
            'username.required'  => 'Username harus diisi.',
            'username.exists'    => 'Username tidak ditemukan.',
            'otp.required'       => 'Kode OTP harus diisi.',
            'otp.digits'         => 'Kode OTP harus 6 digit angka.',
            'password.required'  => 'Password baru harus diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::where('username', $request->username)->firstOrFail();

        // Cek apakah masih dalam masa blokir
        if ($user->otp_attempts >= 5 && $user->otp_expires_at && now()->lt($user->otp_expires_at)) {
            $waitMinutes = now()->diffInMinutes($user->otp_expires_at, false);
            return back()->withErrors(['otp' => "Terlalu banyak percobaan. Coba lagi dalam {$waitMinutes} menit."])
                         ->withInput(['username' => $request->username]);
        }

        // Cek apakah OTP sudah expire
        if (!$user->otp_expires_at || now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Silakan minta kode baru.'])
                         ->withInput(['username' => $request->username]);
        }

        // Cek OTP match
        if ($user->otp_code !== $request->otp) {
            $attempts = $user->otp_attempts + 1;

            if ($attempts >= 5) {
                // Blokir 10 menit mulai sekarang
                $user->update([
                    'otp_attempts'   => $attempts,
                    'otp_expires_at' => now()->addMinutes(10),
                ]);
                return back()->withErrors(['otp' => 'Kode OTP salah. Akun diblokir sementara 10 menit.'])
                             ->withInput(['username' => $request->username]);
            }

            $user->increment('otp_attempts');
            $sisa = 5 - $attempts;
            return back()->withErrors(['otp' => "Kode OTP salah. Sisa percobaan: {$sisa}."])
                         ->withInput(['username' => $request->username]);
        }

        // OTP valid - reset password
        $user->update([
            'password'       => Hash::make($request->password),
            'otp_code'       => null,
            'otp_expires_at' => null,
            'otp_channel'    => null,
            'otp_attempts'   => 0,
        ]);

        return redirect()->route('login')
                         ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}

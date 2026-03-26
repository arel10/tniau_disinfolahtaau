<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SecurityAuditService;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    // ── Setup Page ──────────────────────────────────────────────

    /**
     * Show 2FA setup page (generate secret + QR code).
     */
    public function setup(Request $request)
    {
        $user = Auth::user();

        // If already confirmed, redirect to settings
        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.settings');
        }

        // Generate new secret or use pending one from session
        $secret = session('2fa_setup_secret');
        if (!$secret) {
            $secret = $this->google2fa->generateSecretKey(32);
            session(['2fa_setup_secret' => $secret]);
        }

        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name', 'TNI AU Disinfolahtaau'),
            $user->email ?? $user->username,
            $secret
        );

        $qrCodeSvg = $this->generateQrCodeSvg($qrCodeUrl);

        return view('auth.two-factor-setup', compact('secret', 'qrCodeSvg'));
    }

    /**
     * Confirm 2FA setup by verifying the first OTP.
     */
    public function confirmSetup(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user  = Auth::user();
        $secret = session('2fa_setup_secret');

        if (!$secret) {
            return back()->with('error', 'Sesi setup 2FA telah berakhir. Silakan ulangi.');
        }

        $valid = $this->google2fa->verifyKey($secret, $request->code);

        if (!$valid) {
            return back()->with('error', 'Kode OTP tidak valid. Pastikan jam perangkat Anda sinkron.');
        }

        // Activate 2FA
        $user->two_factor_secret       = $secret;
        $user->two_factor_confirmed_at = now();
        $user->save();

        // Generate recovery codes
        $recoveryCodes = $user->generateRecoveryCodes();

        // Clear setup session
        session()->forget('2fa_setup_secret');
        session(['two_factor_verified' => true]);

        SecurityAuditService::log('2fa_enabled', $user, $request);

        return view('auth.two-factor-recovery-codes', [
            'recoveryCodes' => $recoveryCodes,
            'firstSetup'    => true,
        ]);
    }

    // ── Challenge (Login Verification) ──────────────────────────

    /**
     * Show the 2FA challenge page during login.
     */
    public function challenge()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (session('two_factor_verified')) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Verify the 2FA code during login.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = Auth::user();

        if (!$user || !$user->hasTwoFactorEnabled()) {
            return redirect()->route('login');
        }

        $code = preg_replace('/\s+/', '', $request->code);

        // Try TOTP code first
        if (strlen($code) === 6 && ctype_digit($code)) {
            $valid = $this->google2fa->verifyKey(
                $user->two_factor_secret,
                $code,
                2 // window of 2 (±60 seconds)
            );

            if ($valid) {
                session(['two_factor_verified' => true]);
                SecurityAuditService::log('2fa_verified', $user, $request);
                return redirect()->intended(route('admin.dashboard'));
            }
        }

        // Try recovery code
        if ($user->useRecoveryCode($code)) {
            session(['two_factor_verified' => true]);
            SecurityAuditService::log('2fa_recovery_used', $user, $request, [
                'code_used' => substr($code, 0, 4) . '****',
            ]);
            return redirect()->intended(route('admin.dashboard'))
                ->with('warning', 'Anda menggunakan kode pemulihan. Sisa: ' . count($user->two_factor_recovery_codes ?? []));
        }

        return back()->with('error', 'Kode verifikasi tidak valid.');
    }

    // ── Settings Page ───────────────────────────────────────────

    /**
     * Show 2FA settings (enabled status, regenerate recovery codes, disable).
     */
    public function settings()
    {
        $user = Auth::user();
        return view('auth.two-factor-settings', [
            'enabled'        => $user->hasTwoFactorEnabled(),
            'recoveryCount'  => count($user->two_factor_recovery_codes ?? []),
        ]);
    }

    /**
     * Regenerate recovery codes.
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.setup');
        }

        $recoveryCodes = $user->generateRecoveryCodes();

        SecurityAuditService::log('2fa_recovery_regenerated', $user, $request);

        return view('auth.two-factor-recovery-codes', [
            'recoveryCodes' => $recoveryCodes,
            'firstSetup'    => false,
        ]);
    }

    /**
     * Disable 2FA.
     */
    public function disable(Request $request)
    {
        $request->validate([
            'code'     => 'required|digits:6',
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        // Verify password
        if (!Auth::validate(['username' => $user->username, 'password' => $request->password])) {
            return back()->with('error', 'Password tidak valid.');
        }

        // Verify current OTP
        if (!$this->google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            return back()->with('error', 'Kode OTP tidak valid.');
        }

        // If admin, deny disable
        if ($user->isAdmin()) {
            return back()->with('error', 'Admin tidak dapat menonaktifkan 2FA.');
        }

        $user->two_factor_secret         = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at   = null;
        $user->save();

        SecurityAuditService::log('2fa_disabled', $user, $request);

        return redirect()->route('two-factor.settings')
            ->with('success', 'Autentikasi 2 Faktor telah dinonaktifkan.');
    }

    // ── Helpers ─────────────────────────────────────────────────

    protected function generateQrCodeSvg(string $url): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        return $writer->writeString($url);
    }
}

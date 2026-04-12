<?php

declare(strict_types=1);

namespace App\Domain\Auth\Http\Controllers;

use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class MFAController extends Controller
{
    /**
     * Generate 2FA secret and QR code for setup.
     */
    public function generateSecret(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->google2fa_secret) {
            return Inertia::render('Frontend/Security', [
                'error' => '2FA is already enabled.',
            ]);
        }

        $secret = Google2FA::generateSecretKey();

        // Stash the secret in the session temporarily for verification step
        session(['2fa_setup_secret' => $secret]);

        $qrCodeUrl = Google2FA::getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $imageRenderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd
        );
        $writer = new Writer($imageRenderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        return Inertia::render('Frontend/Security', [
            'mfa_setup' => [
                'secret' => $secret,
                'qr_code' => $qrCodeSvg,
            ],
        ]);
    }

    /**
     * Verify and enable 2FA for the user.
     */
    public function enable(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $secret = session('2fa_setup_secret');

        if (! $secret) {
            return back()->withErrors(['code' => 'Session expired. Please try setup again.']);
        }

        $valid = Google2FA::verifyKey($secret, $request->code);

        if (! $valid) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        /** @var User $user */
        $user = $request->user();
        $user->update(['google2fa_secret' => $secret]);

        session()->forget('2fa_setup_secret');

        return back()->with('success', 'Two-factor authentication has been enabled.');
    }

    /**
     * Disable 2FA for the user.
     */
    public function disable(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->update(['google2fa_secret' => null]);

        return back()->with('success', 'Two-factor authentication has been disabled.');
    }
}

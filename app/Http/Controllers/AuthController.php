<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function redirectToInisiator()
    {
        $query = http_build_query([
            'client_id' => config('services.inisiator.client_id'),
            'redirect_uri' => config('services.inisiator.redirect'),
            'response_type' => 'code',
            'scope' => '',
        ]);

        return redirect(config('services.inisiator.base_url') . '/oauth/authorize?' . $query);
    }

    public function handleInisiatorCallback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect('/login')->with('error', 'Kode otorisasi tidak ditemukan.');
        }

        // 1. Exchange code with access token
        $response = Http::asForm()->post(config('services.inisiator.base_url') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.inisiator.client_id'),
            'client_secret' => config('services.inisiator.client_secret'),
            'redirect_uri' => config('services.inisiator.redirect'),
            'code' => $request->code,
        ]);

        if ($response->failed()) {
            logger()->error('Token exchange failed:', $response->json());
            return redirect('/login')->with('error', 'Gagal mendapatkan token dari Inisiator.');
        }

        $token = $response->json()['access_token'] ?? null;

        if (!$token) {
            return redirect('/login')->with('error', 'Access token tidak tersedia.');
        }

        // 2. Get user info from Inisiator
        $userInfoResponse = Http::withToken($token)
            ->acceptJson()
            ->get(config('services.inisiator.base_url') . '/api/user');

        // dd($userInfoResponse);

        if ($userInfoResponse->failed()) {
            logger()->error('User info request failed:', $userInfoResponse->json());
            return redirect('/login')->with('error', 'Gagal mengambil data pengguna dari Inisiator.');
        }

        $userInfo = $userInfoResponse->json();

        // 3. Create or update local user
        $user = User::updateOrCreate(
            ['email' => $userInfo['email']],
            [
                'name' => $userInfo['name'],
                'password' => bcrypt(Str::random(32)),
            ]
        );

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}

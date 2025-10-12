<?php

// app/Http/Controllers/BeaconController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BeaconController extends Controller
{
    public function store(Request $request)
    {
        $data  = $request->json()->all();
        $token = $data['token'] ?? '';

        if (! $this->isValidToken($token)) {
            return response()->noContent(202); // ignorar silenciosamente
        }

        // marcamos humano por 24h
        Cache::put('human:'.$this->tokenId($token), true, now()->addDay());

        return response()->noContent();
    }

    private function isValidToken(string $token): bool
    {
        $decoded = base64_decode($token, true);
        if (! $decoded) return false;

        $parts = explode('|', $decoded);
        if (count($parts) !== 3) return false;

        [$uuidTime, $ts, $sig] = $parts;
        $calc = hash_hmac('sha256', $uuidTime.'|'.$ts, config('app.key'));
        return hash_equals($calc, $sig);
    }

    private function tokenId(string $token): string
    {
        // ID estable con hash corto para cache keys
        return substr(hash('sha1', $token), 0, 20);
    }
}

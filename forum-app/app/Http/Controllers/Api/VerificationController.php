<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function sendVerification(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Already verified.'], 200);
        }

       defer(function () use ($request) {
            $request->user()->sendEmailVerificationNotification();
        });

        return response()->json(['message' => 'Verification link sent.']);
    }

    public function verify(Request $request, $id, $hash): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('posts.index');
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect()->route('posts.index');
    }

}

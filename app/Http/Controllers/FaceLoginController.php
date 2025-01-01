<?php

//this is face login controller

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Exceptions\FaceLoginException;

class FaceLoginController extends Controller
{
    // Maximum login attempts before temporary lockout
    const MAX_ATTEMPTS = 5;
    // Threshold for face similarity (lower is stricter) - previously is 0.6
    const FACE_SIMILARITY_THRESHOLD = 0.5;

    public function showLoginForm()
    {
        return view('auth.face-login');
    }

    public function verifyFace(Request $request)
    {
        try {
            $request->validate([
                'face_descriptor' => 'required|string',
            ]);

            // Check if user is locked out
            if ($this->isLockedOut($request)) {
                throw new FaceLoginException('Too many failed attempts. Please try again later.');
            }

            // Convert the face descriptor from string back to array
            $faceDescriptor = json_decode($request->face_descriptor);

            // Validate face descriptor format
            if (!$this->isValidFaceDescriptor($faceDescriptor)) {
                throw new FaceLoginException('Invalid face descriptor format');
            }

            // Find user by comparing face descriptors
            $user = User::whereNotNull('face_descriptor')
                ->get()
                ->first(function ($user) use ($faceDescriptor) {
                    $storedDescriptor = json_decode($user->face_descriptor);
                    $distance = $this->calculateDistance($faceDescriptor, $storedDescriptor);
                    return $distance < self::FACE_SIMILARITY_THRESHOLD;
                });

            if (!$user) {
                $this->incrementFailedAttempts($request);
                throw new FaceLoginException('Face not recognized');
            }

            // Record last login time
            $user->update(['last_login' => now()]);

            // Clear failed attempts on successful login
            $this->clearFailedAttempts($request);

            // Log successful login
            // Log::info('Successful face login', ['user_id' => $user->id]);
            Log::info('Successful face login', [
                'user_id' => $user->id,
                'last_login' => $user->last_login
            ]);

            Auth::login($user);

            // Determine redirect path based on user role
            $redirectPath = $this->getRedirectPath($user);

            return response()->json([
                'success' => true,
                'redirect' => $redirectPath,
                'message' => 'Login successful'
            ]);
        } catch (FaceLoginException $e) {
            // ... rest of the error handling remains the same
            Log::warning('Face login failed', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            Log::error('Face login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred'
            ], 500);
        }
    }


    public function fallbackLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Update last login time
            $user->update([
                'last_login' => now()
            ]);
            

            $this->clearFailedAttempts($request);

            // Get the authenticated user
            $user = Auth::user();

            // Determine redirect path based on user role
            $redirectPath = $this->getRedirectPath($user);

            return response()->json([
                'success' => true,
                'redirect' => $redirectPath
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    private function isValidFaceDescriptor($descriptor)
    {
        if (!is_array($descriptor) || count($descriptor) !== 128) {
            return false;
        }

        foreach ($descriptor as $value) {
            if (!is_numeric($value)) {
                return false;
            }
        }

        return true;
    }

    private function calculateDistance($descriptor1, $descriptor2)
    {
        $sum = 0;
        for ($i = 0; $i < count($descriptor1); $i++) {
            $sum += pow($descriptor1[$i] - $descriptor2[$i], 2);
        }
        return sqrt($sum);
    }

    private function isLockedOut(Request $request)
    {
        $key = $this->throttleKey($request);
        $attempts = cache()->get($key, 0);
        return $attempts >= self::MAX_ATTEMPTS;
    }

    private function incrementFailedAttempts(Request $request)
    {
        $key = $this->throttleKey($request);
        $attempts = cache()->get($key, 0) + 1;
        cache()->put($key, $attempts, now()->addMinutes(5));
    }

    private function clearFailedAttempts(Request $request)
    {
        $key = $this->throttleKey($request);
        cache()->forget($key);
    }

    private function throttleKey(Request $request)
    {
        return 'face_login_attempts_' . $request->ip();
    }

    private function getRedirectPath($user)
    {
        switch ($user->role) {
            case 'admin':
                return '/admin/dashboard';
            case 'user':
                return '/users/dashboard';
            default:
                return '/dashboard'; // fallback route
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\FaceRegistrationException;

class FaceRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.face-register');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'face_descriptor' => 'required|string',
            ]);

            DB::beginTransaction();

            try {
                // Verify face descriptor format
                $faceDescriptor = json_decode($request->face_descriptor);
                if (!$this->isValidFaceDescriptor($faceDescriptor)) {
                    throw new FaceRegistrationException('Invalid face descriptor format');
                }

                // Check for existing similar faces
                if ($this->faceAlreadyExists($faceDescriptor)) {
                    throw new FaceRegistrationException('This face is already registered in the system');
                }

                // Create user with face descriptor
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'face_descriptor' => $request->face_descriptor,
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful',
                    'redirect' => route('face.login')
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (FaceRegistrationException $e) {
            Log::warning('Face registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);

        } catch (\Exception $e) {
            Log::error('Face registration error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred during registration'
            ], 500);
        }
    }

    private function isValidFaceDescriptor($descriptor)
    {
        // Check if descriptor is an array of 128 floating point numbers
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

    private function faceAlreadyExists($newDescriptor)
    {
        $users = User::whereNotNull('face_descriptor')->get();
        
        foreach ($users as $user) {
            $storedDescriptor = json_decode($user->face_descriptor);
            $distance = $this->calculateDistance($newDescriptor, $storedDescriptor);
            
            // If distance is less than threshold, consider it a match -- 0.4
            if ($distance < 0.4) {
                return true;
            }
        }
        
        return false;
    }

    private function calculateDistance($descriptor1, $descriptor2)
    {
        $sum = 0;
        for ($i = 0; $i < count($descriptor1); $i++) {
            $sum += pow($descriptor1[$i] - $descriptor2[$i], 2);
        }
        return sqrt($sum);
    }
}
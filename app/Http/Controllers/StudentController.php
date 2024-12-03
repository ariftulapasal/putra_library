<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function faceRegister(Request $request) {
        $descriptor = $request->input('descriptor');
        
        // Store descriptor and other student data
        $student = new Student();
        $student->name = 'Student Name'; // Example - modify as needed
        $student->face_descriptor = json_encode($descriptor);
        $student->save();
        
        return response()->json(['message' => 'Registration successful!']);
    }

    public function faceLogin(Request $request) {
        $descriptor = $request->input('descriptor');
        $students = Student::all();

        foreach ($students as $student) {
            $storedDescriptor = json_decode($student->face_descriptor);
            $distance = faceapi.euclideanDistance(descriptor, storedDescriptor);

            if ($distance < 0.6) {
                return response()->json(['message' => 'Login successful!']);
            }
        }

        return response()->json(['message' => 'Face not recognized']);
    }
}


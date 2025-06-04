<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialServiceStudent;
use Illuminate\Http\Request;

class SocialServiceController extends Controller
{
    // CREATE: cualquier usuario autenticado
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'university' => 'required|string',
            'career' => 'required|string',
            'student_id' => 'required|string',
            'phone' => 'required|string',
            'emergency_contact' => 'required|string',
            'emergency_phone' => 'required|string',
            'schedule' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'activities' => 'required|string',
            'status' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        $student = SocialServiceStudent::create($validated);

        return response()->json($student, 201);
    }

    // READ ALL: solo admin
    public function index()
    {
        return SocialServiceStudent::all();
    }

    // READ ONE: cualquier autenticado
    public function show($id)
    {
        return SocialServiceStudent::findOrFail($id);
    }

    // UPDATE: solo admin
    public function update(Request $request, $id)
    {
        $student = SocialServiceStudent::findOrFail($id);
        $student->update($request->all());
        return response()->json($student);
    }

    // DELETE: solo admin
    public function destroy($id)
    {
        $student = SocialServiceStudent::findOrFail($id);
        $student->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}

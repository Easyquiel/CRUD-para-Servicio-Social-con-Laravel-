<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SocialServiceStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::with(['creator', 'participants', 'socialServiceStudent']);
        
        // Filtros
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('social_service_student_id')) {
            $query->where('social_service_student_id', $request->social_service_student_id);
        }
        
        if ($request->has('start') && $request->has('end')) {
            $query->whereBetween('start', [$request->start, $request->end]);
        }

        return response()->json($query->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'location' => 'nullable|string',
            'color' => 'nullable|string',
            'type' => 'required|in:general,social_service',
            'social_service_student_id' => 'nullable|exists:social_service_students,id',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $eventData = $validator->validated();
        $eventData['user_id'] = Auth::id();
        $eventData['status'] = 'pending';

        $event = Event::create($eventData);

        if ($request->has('participants')) {
            $event->participants()->attach($request->participants);
        }

        return response()->json($event->load('creator', 'participants', 'socialServiceStudent'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return response()->json($event->load('creator', 'participants', 'socialServiceStudent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        // Solo el creador o admin puede actualizar
        if (Auth::id() !== $event->user_id && !Auth::user()->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start' => 'sometimes|date',
            'end' => 'sometimes|date|after:start',
            'location' => 'nullable|string',
            'color' => 'nullable|string',
            'status' => 'sometimes|in:pending,confirmed,cancelled',
            'social_service_student_id' => 'nullable|exists:social_service_students,id',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $event->update($validator->validated());

        if ($request->has('participants')) {
            $event->participants()->sync($request->participants);
        }

        return response()->json($event->load('creator', 'participants', 'socialServiceStudent'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if (Auth::id() !== $event->user_id && !Auth::user()->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $event->delete();
        return response()->json(['message' => 'Evento eliminado']);
    }
}

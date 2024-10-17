<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentRequest;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class AssignmentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): InertiaResponse
    {
        $this->authorize('viewAny', Assignment::class);

        $assignments = $request->user()->hasRole('admin')
            ? Assignment::with('user')->get()
            : $request->user()->assignments()->get();

        return Inertia::render('Assignments/Index', [
            'assignments' => $assignments,
            'isAdmin' => $request->user()->hasRole('admin'),
        ]);
    }

    public function store(AssignmentRequest $request): JsonResponse
    {
        $this->authorize('create', Assignment::class);

        $valid = $request->validated();

        $valid['user_id'] = $request->user()->id;

        $assignment = Assignment::create($valid);

        return response()->json(['assignment' => $assignment->id]);
    }

    public function create(): InertiaResponse
    {
        $this->authorize('create', Assignment::class);

        return Inertia::render('Assignments/Create');
    }

    public function show(Assignment $assignment): InertiaResponse
    {
        $this->authorize('view', $assignment);

        $receiver = auth()->user()->hasRole('admin') ? $assignment->user : User::admin();

        return Inertia::render('Assignments/Show', [
            'assignment' => $assignment,
            'currentUser' => auth()->user(),
            'receiver' => $receiver,
        ]);
    }

    public function edit(Assignment $assignment): InertiaResponse
    {
        $this->authorize('update', $assignment);

        return Inertia::render('Assignments/Edit', [
            'assignment' => $assignment,
        ]);
    }

    public function update(AssignmentRequest $request, Assignment $assignment): JsonResponse
    {
        $this->authorize('update', $assignment);

        $assignment->update($request->validated());

        return response()->json(['success' => 'Assignment updated.', 'assignment' => $assignment]);
    }

    public function destroy(Assignment $assignment)
    {
        $this->authorize('delete', $assignment);

        $assignment->delete();

        return response()->json(['success' => 'Assignment deleted.']);
    }
}

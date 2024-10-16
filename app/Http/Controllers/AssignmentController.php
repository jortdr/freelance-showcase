<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentRequest;
use App\Models\Assignment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class AssignmentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): InertiaResponse
    {
        $this->authorize('viewAny', Assignment::class);

        return Inertia::render('Assignments/Index', [
            'assignments' => $request->user()->assignments()->with('user')->get(),
        ]);
    }

    public function store(AssignmentRequest $request): RedirectResponse
    {
        $this->authorize('create', Assignment::class);

        $assignment = Assignment::create($request->validated());

        return redirect()->route('assignments.show', ['assignment' => $assignment->id])->with('success', 'Assignment created.');
    }

    public function create(): InertiaResponse
    {
        $this->authorize('create', Assignment::class);

        return Inertia::render('Assignments/Create');
    }

    public function show(Assignment $assignment): InertiaResponse
    {
        $this->authorize('view', $assignment);

        return Inertia::render('Assignments/Show', [
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

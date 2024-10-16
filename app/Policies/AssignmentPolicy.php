<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Assignment $assignment): bool
    {
        return $user->hasRole('admin') || $user->id === $assignment->user_id;
    }

    public function create(User $user): bool
    {
        return $user->email_verified_at !== null;
    }

    public function update(User $user, Assignment $assignment): bool
    {
        return $user->id === $assignment->user_id;
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        return $user->id === $assignment->user_id;
    }

    public function restore(User $user, Assignment $assignment): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Assignment $assignment): bool
    {
        return $user->hasRole('admin');
    }
}

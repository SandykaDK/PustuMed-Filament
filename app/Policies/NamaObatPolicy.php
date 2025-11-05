<?php

namespace App\Policies;

use App\Models\NamaObat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NamaObatPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, NamaObat $namaObat): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NamaObat $namaObat): bool
    {
        // $currentUser = Auth::user();
        // return $currentUser->id == 1 ? true : false;
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NamaObat $namaObat): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, NamaObat $namaObat): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, NamaObat $namaObat): bool
    {
        return true;
    }
}

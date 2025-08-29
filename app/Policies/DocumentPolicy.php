<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Document;

class DocumentPolicy
{
    /**
     * Determine if the given document can be updated by the user.
     */
    public function update(User $user, Document $document): bool
    {
        return $user->hasRole(['Administrasi', 'Administrator']);
    }

    /**
     * Determine if the given document can be deleted by the user.
     */
    public function delete(User $user, Document $document): bool
    {
        return $user->hasRole(['Administrasi', 'Administrator']);
    }
}

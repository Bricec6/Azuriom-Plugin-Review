<?php

namespace Azuriom\Plugin\Review\Policies;

use Azuriom\Models\User;
use Azuriom\Plugin\Review\Models\Review;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create review.
     */
    public function create(User $user): bool
    {
        return $user->can('review.create');
    }

    /**
     * Determine whether the user can delete the review.
     */
    public function delete(User $user, Review $review): bool
    {
        return $user->is($review->author) || $user->can('review.delete.other');
    }
}

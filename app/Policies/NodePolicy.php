<?php

namespace App\Policies;

use App\Models\Node;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NodePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Public or auth? Assuming auth via route middleware, but map might be public.
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Node $node): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('global_admin') || ($user->hasRole('node_admin') && $user->nodes()->count() == 0); // Node admin limited to 1 node? Or created by global admin?
        // Request says "Node_admin hanya boleh mengelola node miliknya". Usually they come with a node or create one.
        // Let's allow creating for now if they have permission.
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Node $node): bool
    {
        if ($user->hasRole('global_admin')) {
            return true;
        }
        return $user->id === $node->user_id && $user->hasRole('node_admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Node $node): bool
    {
        if ($user->hasRole('global_admin')) {
            return true;
        }
        return $user->id === $node->user_id && $user->hasRole('node_admin');
    }
}

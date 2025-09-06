<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;

class WalletPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true; // Users can view their own wallets
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Wallet $wallet)
    {
        return $user->id === $wallet->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Users can create wallets if they don't have more than 5
        return $user->wallets()->count() < 5;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Wallet $wallet)
    {
        return $user->id === $wallet->user_id && $wallet->is_active;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Wallet $wallet)
    {
        return $user->id === $wallet->user_id && 
               $wallet->balance == 0 && 
               $wallet->pending_balance == 0;
    }

    /**
     * Determine whether the user can perform transactions.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function transact(User $user, Wallet $wallet)
    {
        return $user->id === $wallet->user_id && 
               $wallet->is_active && 
               $wallet->status === 'active' &&
               $wallet->is_verified;
    }

    /**
     * Determine whether the user can freeze/unfreeze the wallet.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function freeze(User $user, Wallet $wallet)
    {
        return $user->id === $wallet->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view transactions.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewTransactions(User $user, Wallet $wallet)
    {
        return $user->id === $wallet->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can export transactions.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function exportTransactions(User $user, Wallet $wallet)
    {
        return $user->id === $wallet->user_id;
    }
}
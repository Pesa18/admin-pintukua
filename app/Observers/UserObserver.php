<?php

namespace App\Observers;

use App\Models\Team;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if (auth()->hasUser()) {
            $user->team_id = getPermissionsTeamId();
            // or with a `team` relationship defined:
            $user->save();

            // Ambil tim berdasarkan team_id yang baru saja disetel
            $team = Team::find($user->team_id);

            // Pastikan tim ditemukan sebelum mengaitkan anggota
            if ($team) {
                // Mengaitkan pengguna dengan tim
                $team->members()->attach($user->id);
            }

            // $user->team()->associate(auth()->user()->teams);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}

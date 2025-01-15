<?php

namespace App\Observers;

use App\Models\Article;

class ArticleObserver
{
    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {

        if (auth()->hasUser()) {
            $article->team_id = auth()->user()->team_id;
            // or with a `team` relationship defined:
            $article->team()->associate(auth()->user()->team);
            $article->save();
        }
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        //
    }
}

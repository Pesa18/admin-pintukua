<?php

namespace App\Models;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use auth;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Article extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ['id'];
    protected $primaryKey = 'uuid';


    public function categories(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'category_id', 'id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tags');
    }

    public function viewers(): HasMany
    {
        return $this->hasMany(ArticleViews::class, 'article_id', 'id');
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('team', function (Builder $query) {
            if (auth()->hasUser()) {
                $query->where('team_id', getPermissionsTeamId());
                // or with a `team` relationship defined:
                $query->whereBelongsTo(auth()->user()->teams);
            }
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withoutGlobalScopes();
    }
    public function kua()
    {
        return $this->hasMany(KuaTeam::class, 'user_id', 'user_id');
    }
}

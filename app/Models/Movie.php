<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Movie.
 *
 * @package namespace App\Models;
 */
class Movie extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'release_date',
        'rating',
        'country',
        'genre',
        'photo',
    ];

    protected $casts = [
        'genre' => 'array',
        'release_date' => 'date',
    ];

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'movie_id')
            ->whereNull('comment_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * @return HasMany
     */
    public function characters(): HasMany
    {
        return $this->hasMany(MovieCharacter::class, 'movie_id');
    }

}

<?php

namespace Azuriom\Plugin\Review\Models;

use Azuriom\Games\Minecraft\AbstractMinecraftGame;
use Azuriom\Models\Traits\HasTablePrefix;
use Azuriom\Models\Traits\HasUser;
use Azuriom\Models\Traits\Loggable;
use Azuriom\Models\Traits\Searchable;
use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $type
 * @property string|null $source
 * @property string|null $source_id
 * @property string|null $source_url
 * @property int|null $author_id
 * @property string|null $author_name
 * @property int|null $rating
 * @property string|null $title
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Azuriom\Models\User|null $author
 *
 * @method static \Illuminate\Database\Eloquent\Builder local()
 * @method static \Illuminate\Database\Eloquent\Builder imported()
 */
class Review extends Model
{
    use HasTablePrefix;
    use HasUser;
    use Loggable;
    use Searchable;

    /**
     * A review written by a member of the website.
     */
    public const TYPE_LOCAL = 'local';

    /**
     * A review imported from a server listing.
     */
    public const TYPE_IMPORTED = 'imported';

    /**
     * The table prefix associated with the model.
     *
     * @var string
     */
    protected $prefix = 'reviews_';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'rating', 'title', 'content',
        'source', 'source_id', 'source_url', 'author_name', 'created_at',
    ];

    /**
     * The attributes that can be used for search.
     *
     * @var array<int, string>
     */
    protected array $searchable = [
        'author.name', 'author_name',
    ];

    /**
     * The user key associated with this model.
     *
     * @var string
     */
    protected $userKey = 'author_id';

    /**
     * Get the user who created this review.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function isImported(): bool
    {
        return $this->type === self::TYPE_IMPORTED;
    }

    /**
     * Get the display name of the author, either the site user or the
     * nickname provided by the listing for an imported review.
     */
    public function authorName(): string
    {
        return $this->author?->name
            ?? $this->author_name
            ?? trans('review::messages.anonymous');
    }

    /**
     * Get the avatar url of the author, built from the nickname given by the
     * listing when the review doesn't belong to a site user.
     */
    public function getAvatar(int $size = 64): string
    {
        if ($this->author !== null) {
            return $this->author->getAvatar($size);
        }

        if ($this->author_name !== null && game() instanceof AbstractMinecraftGame) {
            return 'https://mc-heads.net/avatar/'.urlencode($this->author_name)."/{$size}.png";
        }

        return asset('svg/user.svg');
    }

    /**
     * Scope a query to only include reviews written on this website.
     */
    public function scopeLocal(Builder $query): void
    {
        $query->where('type', self::TYPE_LOCAL);
    }

    /**
     * Scope a query to only include reviews imported from a listing.
     */
    public function scopeImported(Builder $query): void
    {
        $query->where('type', self::TYPE_IMPORTED);
    }
}

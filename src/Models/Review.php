<?php
namespace Azuriom\Plugin\Review\Models;

use Azuriom\Models\Traits\HasTablePrefix;
use Azuriom\Models\Traits\HasUser;
use Azuriom\Models\Traits\Loggable;
use Azuriom\Models\Traits\Searchable;
use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasTablePrefix;
    use HasUser;
    use Loggable;
    use Searchable;

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
        'rating', 'title', 'content',
    ];


    /**
     * The attributes that can be used for search.
     *
     * @var array<int, string>
     */
    protected array $searchable = [
        'author.name',
    ];

    /**
     * The user key associated with this model.
     *
     * @var string
     */
    protected $userKey = 'author_id';

    /**
     * Get the user who created this ticket.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}

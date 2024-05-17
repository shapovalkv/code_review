<?php


namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DashboardNotice extends Model
{
    public const RELATION_USERS = 'users';

    public const SUCCESS = 'success';
    public const DANGER = 'danger';
    public const PRIMARY = 'primary';
    public const WARNING = 'warning';

    public const PUBLISH = 'publish';
    public const DRAFT = 'draft';

    protected $attributes = [
        'sort'   => 0,
        'status' => self::PUBLISH
    ];

    protected $fillable = [
        'title',
        'content',
        'sort',
        'status',
        'style',
        'filter',
    ];

    protected $casts = [
        'filter' => 'array',
    ];

    /**
     * read by users
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, null, 'notice_id')->using(DashboardNoticeUser::class);
    }
}

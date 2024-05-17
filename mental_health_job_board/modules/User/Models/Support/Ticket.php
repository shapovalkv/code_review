<?php


namespace Modules\User\Models\Support;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Ticket extends Model
{
    use SoftDeletes;

    public const RELATION_MESSAGES = 'messages';

    public const NEW = 'new';
    public const ANSWERED = 'answered'; // admin answered
    public const WAITING = 'waiting'; // waiting for admin answer
    public const COMPLETED = 'completed';

    protected $dates = ['created_at', 'updated_at'];

    protected $attributes = [
        'status' => self::NEW,
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class)->orderBy('created_at');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Http\Chat\Models\Chat;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use App\Http\Chat\Models\Chat\Traits\Method\ConversationMethod;
use App\Http\Chat\Models\Chat\Traits\Relationship\ConversationRelationship;
use Musonza\Chat\Models\Conversation as Base;


/**
 * App\Domains\Core\Models\Chat\Conversation
 *
 * @property int $id
 * @property bool $private
 * @property bool $direct_message
 * @property array|null $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int|null $abuse_reports_count
 * @property-read \Musonza\Chat\Models\Message|null $last_message
 * @property-read Collection| Message[] $messages
 * @property-read int|null $messages_count
 * @property-read Collection| MessageNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|\Musonza\Chat\Models\Participation[] $participants
 * @property-read int|null $participants_count
 * @method static Builder|Conversation newModelQuery()
 * @method static Builder|Conversation newQuery()
 * @method static Builder|Conversation query()
 * @method static Builder|Conversation whereCreatedAt($value)
 * @method static Builder|Conversation whereData($value)
 * @method static Builder|Conversation whereDirectMessage($value)
 * @method static Builder|Conversation whereId($value)
 * @method static Builder|Conversation wherePrivate($value)
 * @method static Builder|Conversation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Conversation extends Base
{
    use ConversationMethod,
        ConversationRelationship;
}

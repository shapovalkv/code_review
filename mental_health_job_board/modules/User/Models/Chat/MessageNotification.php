<?php

namespace Modules\User\Models\Chat;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\User\Models\Chat\Traits\Relationship\MessageNotificationRelationship;
use Musonza\Chat\Models\MessageNotification as Base;

/**
 * App\Domains\Core\Models\Chat\MessageNotification
 *
 * @property int $id
 * @property int $message_id
 * @property int $messageable_id
 * @property string $messageable_type
 * @property int $conversation_id
 * @property int $participation_id
 * @property int $is_seen
 * @property int $is_sender
 * @property int $flagged
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Model|\Eloquent $messageable
 * @method static Builder|MessageNotification newModelQuery()
 * @method static Builder|MessageNotification newQuery()
 * @method static Builder|MessageNotification query()
 * @method static Builder|MessageNotification whereConversationId($value)
 * @method static Builder|MessageNotification whereCreatedAt($value)
 * @method static Builder|MessageNotification whereDeletedAt($value)
 * @method static Builder|MessageNotification whereFlagged($value)
 * @method static Builder|MessageNotification whereId($value)
 * @method static Builder|MessageNotification whereIsSeen($value)
 * @method static Builder|MessageNotification whereIsSender($value)
 * @method static Builder|MessageNotification whereMessageId($value)
 * @method static Builder|MessageNotification whereMessageableId($value)
 * @method static Builder|MessageNotification whereMessageableType($value)
 * @method static Builder|MessageNotification whereParticipationId($value)
 * @method static Builder|MessageNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MessageNotification extends Base
{
    use MessageNotificationRelationship;
}

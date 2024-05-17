<?php

namespace App\Http\Chat\Models\Chat;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use App\Http\Chat\Models\Chat\Traits\Attribute\MessageAttribute;
use App\Http\Chat\Models\Chat\Traits\Relationship\MessageRelationship;
use Musonza\Chat\Models\Message as Base;
use Musonza\Chat\Models\Participation;

/**
 * App\Domains\Core\Models\Chat\Message
 *
 * @property int $id
 * @property string $body
 * @property int $conversation_id
 * @property int|null $participation_id
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Conversation $conversation
 * @property-read mixed $sender
 * @property-read mixed $time
 * @property-read Participation|null $participation
 * @method static Builder|Message newModelQuery()
 * @method static Builder|Message newQuery()
 * @method static Builder|Message query()
 * @method static Builder|Message whereBody($value)
 * @method static Builder|Message whereConversationId($value)
 * @method static Builder|Message whereCreatedAt($value)
 * @method static Builder|Message whereId($value)
 * @method static Builder|Message whereParticipationId($value)
 * @method static Builder|Message whereType($value)
 * @method static Builder|Message whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Message extends Base
{
    use MessageRelationship, MessageAttribute;
}

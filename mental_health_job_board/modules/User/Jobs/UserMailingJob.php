<?php

namespace Modules\User\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\User\Emails\UserMailing;
use Modules\User\Enums\UserStatusEnum;
use Modules\User\ValueObjects\MailingFilter;

class UserMailingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private MailingFilter $mailingFilter;
    private string $subject;
    private string $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MailingFilter $mailingFilter, string $subject, string $body)
    {
        $this->mailingFilter = $mailingFilter;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function handle(): void
    {
        $builder = User::query();

        if ($this->mailingFilter->getRoleIds() !== []) {
            $builder->whereIn('role_id', $this->mailingFilter->getRoleIds());
        }

        if ($this->mailingFilter->getRegisterFrom()) {
            $builder->where('created_at', '>=', $this->mailingFilter->getRegisterFrom()->startOfDay());
        }

        if ($this->mailingFilter->getStatus()) {
            $builder->where(static function (Builder $query) {
                $query->whereHas(User::RELATION_CANDIDATE, static function (Builder $relation) {
                    $relation->where('status', $this->mailingFilter->getStatus());
                    if ($this->mailingFilter->getStatus() === UserStatusEnum::DRAFT) {
                        $relation->orWhereNull('status');
                    }
                })
                    ->orWhereHas(User::RELATION_COMPANY, static function (Builder $relation) {
                        $relation->where('status', $this->mailingFilter->getStatus());
                        if ($this->mailingFilter->getStatus() === UserStatusEnum::DRAFT) {
                            $relation->orWhereNull('status');
                        }
                    });
            });
        }

        $users = $builder->get();

        /** @var User $user */
        foreach ($users as $user) {
            Mail::to($user->email)->queue(new UserMailing($user, $this->body));
        }
    }
}

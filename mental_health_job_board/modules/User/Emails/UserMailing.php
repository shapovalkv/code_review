<?php

namespace Modules\User\Emails;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMailing extends Mailable
{
    use Queueable, SerializesModels;

    public const CODE = [
        'first_name'    => '[first_name]',
        'last_name'     => '[last_name]',
        'name'          => '[name]',
        'email'         => '[email]',
    ];

    private User $user;
    private string $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $content)
    {
        $this->user = $user;
        $this->content = $content;
    }

    public function build(): Mailable
    {
        return $this->view('User::emails.mailing', ['content' => self::replaceContentEmail($this->user, $this->content)]);
    }

    public static function replaceContentEmail(User $user, string $content): string
    {
        if (!empty($content)) {
            foreach (self::CODE as $item => $value) {
                $content = str_replace($value, @$user->$item, $content);
            }
        }

        return $content;
    }
}

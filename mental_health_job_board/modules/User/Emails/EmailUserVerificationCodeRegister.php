<?php

namespace Modules\User\Emails;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailUserVerificationCodeRegister extends Mailable
{
    use Queueable, SerializesModels;

    const CODE = [
        'first_name'        => '[first_name]',
        'last_name'         => '[last_name]',
        'name'              => '[name]',
        'email'             => '[email]',
        'verification_code' => '[verification_code]',
    ];

    private User $user;
    private string $code;

    public function __construct(User $user, string $code)
    {
        $this->user = $user;
        $this->code = $code;
    }


    public function build(): Mailable
    {
        $subject = setting_item("subject_email_verify_register_user");
        if (empty(setting_item("subject_email_verify_register_user"))) {
            $subject = __('[:site_name] Verify Registration', ['site_name' => setting_item('site_title')]);
        } else {
            $subject = $this->replaceSubjectEmail($subject);
        }

        if (!empty(setting_item('content_email_verify_register_user'))) {
            $body = $this->replaceContentEmail(setting_item_with_lang('content_email_verify_register_user', app()->getLocale()));
        } else {
            $body = $this->replaceContentEmail($this->defaultBody());
        }
        return $this->subject($subject)->view('User::emails.verify-registered')->with(['content' => $body]);
    }

    public function replaceSubjectEmail($subject)
    {
        if (!empty($subject)) {
            foreach (self::CODE as $item => $value) {
                if (method_exists($this, $item)) {
                    $replace = $this->$item();
                } else {
                    $replace = '';
                }
                $subject = str_replace($value, $replace, $subject);
            }
        }
        return $subject;
    }

    public function replaceContentEmail($content)
    {
        if (!empty($content)) {
            foreach (self::CODE as $item => $value) {

                if ($item === "verification_code") {
                    $content = str_replace($value, $this->verifyCode(), $content);
                }

                $content = str_replace($value, @$this->user->$item, $content);
            }
        }
        return $content;
    }


    public function defaultBody(): string
    {
        return '
            <h1>Hi, [first_name]</h1>
            <p>Welcome to Mental Healthcare Careers!</p>
            <p>To verify your email address and activate your account, please enter the following code on the <a href="' . route('verification.notice') . '" target="_blank">site page</a>:</p>
            <p style="text-align: center">' . $this->verifyCode() . '</p>
            <p>Sincerely,<br>' . setting_item('site_title') . '</p>';
    }

    public function verifyCode(): string
    {
        return '<h1 style="display: inline-block; text-decoration: none;">' . $this->code . '</h1>';
    }

}

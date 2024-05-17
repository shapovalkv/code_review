<?php

    namespace Modules\User\Emails;

    use App\User;
    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;

    class RegisteredEmail extends Mailable
    {
        use Queueable, SerializesModels;

        public $user;
        public $content;
        public $to_address;

        public function __construct(User $user, $content, $to_address)
        {
            $this->user = $user;
            $this->content = $content;
            $this->to_address = $to_address;
        }

        public function build()
        {
            $subject = __('New user has been registered at :site_name',['site_name'=>setting_item('site_title')]);
            return $this->subject($subject)->view('User::emails.registered')->with([
                'subject' => $subject,
                'user'    => $this->user,
                'content' => $this->content,
                'to'      => $this->to_address,
            ]);
        }
    }

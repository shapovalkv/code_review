<?php

	namespace Modules\User\Emails;

	use Illuminate\Bus\Queueable;
	use Illuminate\Mail\Mailable;
	use Illuminate\Queue\SerializesModels;
    use Modules\User\Listeners\SendMailUserPlanExpired;

    class EmailUserPlanExpired extends Mailable
	{
        use Queueable, SerializesModels;

        protected $content;

        public function __construct($body)
        {
            $this->content = $body;
        }

        public function build()
        {
            $subject = setting_item("subject_email_user_plan_expired");
            if (empty(setting_item("subject_email_user_plan_expired"))) {
                $subject = __('[:site_name] Your Subscription Plan has expired', ['site_name' => setting_item('site_title')]);
            } else {
                $subject = $this->replaceSubjectEmail($subject);
            }
            return $this->subject($subject)->view('User::emails.plan-expired', ['content' => $this->content]);
        }

        public function replaceSubjectEmail($subject)
        {
            if (!empty($subject)) {
                foreach (SendMailUserPlanExpired::CODE as $item => $value) {
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
	}


<?php

namespace Modules\User\Emails;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserPermanentlyDelete extends Mailable
{
    use Queueable, SerializesModels;

    const CODE_CANDIDATE = [
        'first_name'    => '[first_name]',
        'last_name'     => '[last_name]',
        'name'          => '[name]',
        'email'         => '[email]',
    ];

    const CODE_COMPANY = [
        'first_name' => '[first_name]',
        'last_name' => '[last_name]',
        'name' => '[name]',
        'email' => '[email]',
        'company_name' => '[company_name]',
    ];

    public $user;
    public $to_admin;
    public $subject;
    public $content;

    public function __construct(User $user, $to_admin)
    {
        $this->user = $user;
        $this->to_admin = $to_admin;
    }


    public function build()
    {
        if ( $this->user->hasRole('candidate')){
            $data = [
                'first_name' =>  $this->user->first_name ?? '',
                'last_name' => $this->user->last_name ?? '',
                'name' => $this->user->name ?? '',
                'email' => $this->user->email ?? '',
            ];

            $subject = $this->to_admin ? setting_item_with_lang('user_permanently_delete_subject_email_to_admin') : setting_item_with_lang('user_permanently_delete_subject_email');
            $body = $this->to_admin ? $this->replaceContentEmail($data, setting_item_with_lang('user_permanently_delete_content_email_to_admin')) : $this->replaceContentEmail($data, setting_item_with_lang('user_permanently_delete_content_email'));

        } elseif ($this->user->hasRole('employer')){
            $data =  [
                'first_name' =>  $this->user->first_name ?? '',
                'last_name' => $this->user->last_name ?? '',
                'name' => $this->user->name ?? '',
                'email' => $this->user->email ?? '',
                'company_name' => $this->user->company->name ?? 'CompanyId:'.$this->user->company->id,
            ];

            $subject = $this->to_admin ? setting_item_with_lang('company_permanently_delete_subject_email') : setting_item_with_lang('company_permanently_delete_subject_email_to_admin');
            $body = $this->to_admin ? $this->replaceContentEmail($data, setting_item_with_lang('company_permanently_delete_content_email_to_admin')) : $this->replaceContentEmail($data, setting_item_with_lang('company_permanently_delete_content_email'));
        }

        return $this
            ->subject($subject)
            ->view('User::emails.user-permanently-delete')
            ->with(['body' => $body]);
    }

    public function replaceSubjectEmail($subject)
    {
        if (!empty($subject)) {
            foreach ($this->getRoleCode() as $item => $value) {
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

    public function replaceContentEmail($data, $content)
    {
        if (!empty($content)) {
            foreach ($this->getRoleCode() as $item => $value) {
                $content = str_replace($value, @$data[$item], $content);
            }
        }
        return $content;
    }

    public function getRoleCode()
    {
        $content_code = array();
        if ( $this->user->hasRole('candidate')){
            $content_code = self::CODE_CANDIDATE;
        } elseif ($this->user->hasRole('employer')){
            $content_code = self::CODE_COMPANY;
        }

        return $content_code;
    }
}

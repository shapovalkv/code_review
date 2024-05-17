<?php
namespace  Modules\User;

use Modules\Core\Abstracts\BaseSettingsClass;

class SettingClass extends BaseSettingsClass
{
    public static function getSettingPages()
    {
        return [
            [
                'id'   => 'user',
                'title' => __("User Settings"),
                'position'=>50,
                'view'=>"User::admin.settings.user",
                "keys"=>[
                    'user_enable_login_recaptcha',
                    'user_enable_register_recaptcha',
                    'enable_mail_user_registered',
                    'user_content_email_registered',
                    'admin_enable_mail_user_registered',
                    'admin_content_email_user_registered',
                    'user_content_email_forget_password',
                    'inbox_enable',
                    'subject_email_verify_register_user',
                    'content_email_verify_register_user',
                    'enable_verify_email_register_user',
                    'staff_content_email_registered',

                    'user_enable_permanently_delete',
                    'user_permanently_delete_content',
                    'user_permanently_delete_content_confirm',

                    'user_enable_permanently_delete_email',
                    'user_permanently_delete_subject_email',
                    'user_permanently_delete_content_email',

                    'user_permanently_delete_subject_email_to_admin',
                    'user_permanently_delete_content_email_to_admin',

                    'company_permanently_delete_subject_email',
                    'company_permanently_delete_content_email',

                    'company_permanently_delete_subject_email_to_admin',
                    'company_permanently_delete_content_email_to_admin',

                ],
                'html_keys'=>[

                ]
            ],
            [
                'id'   => 'user_plans',
                'title' => __("User Plans Settings"),
                'position'=>51,
                'view'=>"User::admin.settings.plan",
                "keys"=>[
                    'user_plans_page_title',
                    'user_plans_page_sub_title',
                    'user_plans_sale_text',
                    'subject_email_user_plan_expired',
                    'content_email_user_plan_expired'
                ],
                'html_keys'=>[

                ]
            ]
        ];
    }
}

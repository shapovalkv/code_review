<?php
namespace  Modules\MarketplaceUser;

use Modules\Core\Abstracts\BaseSettingsClass;

class SettingClass extends BaseSettingsClass
{
    public static function getSettingPages()
    {
        return [
            [
                'id'   => 'marketplace_user',
                'title' => __("Marketplace User Settings"),
                'position'=>30,
                'view'=>"MarketplaceUser::admin.settings.marketplace_user",
                "keys"=>[
                    'marketplace_user_page_search_title',
                    'marketplace_user_download_cv_required_login',
                    'marketplace_user_public_policy',
                    'marketplace_user_limit_apply_by',
                    'marketplace_user_maximum_job_apply',
                    'marketplace_users_list_layout',
                    'single_marketplace_user_layout',
                    'marketplace_user_sidebar_search_fields',
                    'marketplace_user_location_search_style',
                    'marketplace_user_page_list_seo_title',
                    'marketplace_user_page_list_seo_desc',
                    'marketplace_user_page_list_seo_image',
                    'marketplace_user_page_list_seo_share',
                    'marketplace_user_sidebar_cta',
                    'marketplace_user_single_layout',
                    'marketplace_user_list_layout'
                ],
                'html_keys'=>[

                ]
            ]
        ];
    }
}

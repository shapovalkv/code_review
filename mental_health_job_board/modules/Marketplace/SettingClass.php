<?php

namespace Modules\Marketplace;

use Modules\Core\Abstracts\BaseSettingsClass;
use Modules\Core\Models\Settings;

class SettingClass extends BaseSettingsClass
{
    public static function getSettingPages()
    {
        return [
            [
                'id' => 'Marketplace',
                'title' => __("Marketplace Settings"),
                'position' => 20,
                'view' => "Marketplace::admin.settings.marketplace",
                "keys" => [
                    'Marketplace_disable',
                    'Marketplace_page_search_title',
                    'Marketplace_page_search_banner',
                    'Marketplace_page_limit_item',

                    'Marketplace_enable_review',
                    'Marketplace_review_number_per_page',
                    'Marketplace_review_stats',

                    'Marketplace_page_list_seo_title',
                    'Marketplace_page_list_seo_desc',
                    'Marketplace_page_list_seo_image',
                    'Marketplace_page_list_seo_share',
                    'Marketplace_booking_buyer_fees',


                    'vendor_commission_type',
                    'vendor_commission_amount',

                    'Marketplace_booking_type',
                    'Marketplace_icon_marker_map',
                    'Marketplace_days_complete_order',
                    'Marketplace_max_posts',

                    'marketplace_page_title',
                    'marketplace_page_sub_title',

                    'marketplace_trainings_title',
                    'marketplace_trainings_sub_title',
                    'marketplace_trainings_link',
                    'marketplace_trainings_desc',
                    'marketplace_trainings_img',

                    'marketplace_subLeasing_title',
                    'marketplace_subLeasing_sub_title',
                    'marketplace_subLeasing_link',
                    'marketplace_subLeasing_desc',
                    'marketplace_subLeasing_img',

                    'marketplace_professionalAssistance_title',
                    'marketplace_professionalAssistance_sub_title',
                    'marketplace_professionalAssistance_link',
                    'marketplace_professionalAssistance_desc',
                    'marketplace_professionalAssistance_img',

                ],
                'html_keys' => [

                ]
            ]
        ];
    }
}

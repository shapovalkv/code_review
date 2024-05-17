<?php

namespace Modules\Equipment;

use Modules\Core\Abstracts\BaseSettingsClass;
use Modules\Core\Models\Settings;

class SettingClass extends BaseSettingsClass
{
    public static function getSettingPages()
    {
        return [
            [
                'id' => 'equipment',
                'title' => __("Equipment Settings"),
                'position' => 20,
                'view' => "Equipment::admin.settings.equipment",
                "keys" => [
                    'equipment_disable',
                    'equipment_page_search_title',
                    'equipment_page_search_banner',
                    'equipment_page_limit_item',

                    'equipment_enable_review',
                    'equipment_review_number_per_page',
                    'equipment_review_stats',

                    'equipment_page_list_seo_title',
                    'equipment_page_list_seo_desc',
                    'equipment_page_list_seo_image',
                    'equipment_page_list_seo_share',
                    'equipment_booking_buyer_fees',


                    'vendor_commission_type',
                    'vendor_commission_amount',

                    'equipment_booking_type',
                    'equipment_icon_marker_map',
                    'equipment_days_complete_order',
                    'equipment_max_posts'

                ],
                'html_keys' => [

                ]
            ]
        ];
    }
}

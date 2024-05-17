<?php
namespace  Modules\Payout;

use Modules\Core\Abstracts\BaseSettingsClass;

class SettingClass extends BaseSettingsClass
{
    public static function getSettingPages()
    {
        return [
            [
                'id'   => 'payout',
                'title' => __("Payout Settings"),
                'position' => 30,
                'view'=>"Payout::admin.settings.payout",
                "keys"=>[
                    'vendor_payout_methods',
                    'disable_payout',
                ],
                'html_keys'=>[

                ]
            ]
        ];
    }
}

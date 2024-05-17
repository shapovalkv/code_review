<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePopupsForMarketplaceUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('core_settings')->insert(
            [
                'name'  => 'marketplace_user_welcome_text',
                'val'   => '<p style="margin: 0.75rem 0px 0px; padding: 0px; font-size: 14px; line-height: 1.714; letter-spacing: -0.005em; color: #172b4d; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, \'Fira Sans\', \'Droid Sans\', \'Helvetica Neue\', sans-serif; white-space: pre-wrap; background-color: #ffffff; text-align: center;" data-renderer-start-pos="313">Welcome to Mental Healthcare Careers!<br />We are excited to have you in the Community! We focus on our core values of privacy protection, industry connection and simplicity. These three pillars we believe in whole heartily and this will ensure your ultimate user experience.</p>
                            <p style="margin: 0.75rem 0px 0px; padding: 0px; font-size: 14px; line-height: 1.714; letter-spacing: -0.005em; color: #172b4d; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, \'Fira Sans\', \'Droid Sans\', \'Helvetica Neue\', sans-serif; white-space: pre-wrap; background-color: #ffffff; text-align: center;" data-renderer-start-pos="624">As a Marketplace User, you can start forming your announcements at our marketplace right now!</p>
                            <p style="margin: 0.75rem 0px 0px; padding: 0px; font-size: 14px; line-height: 1.714; letter-spacing: -0.005em; color: #172b4d; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, \'Fira Sans\', \'Droid Sans\', \'Helvetica Neue\', sans-serif; white-space: pre-wrap; background-color: #ffffff; text-align: center;" data-renderer-start-pos="1123">Thank you for joining the MHC Community and best of luck finding either a personalized job or practicum site that meet your desired work style, schedule, compensation and career path.</p>'
            ]
        );
        DB::table('core_settings')->insert(
            [
                'name'  => 'marketplace_user_tutorial_text',
                'val'   => '<p>&nbsp;</p>
<p style="text-align: left;"><span style="font-size: 18pt;"><span style="text-align: center;">Welcome to Mental Healthcare Careers, we are excited to have you in our community! Our core values focus on privacy protection, industry connection and simplicity. These three pillars  will ensure your ultimate user experience.<br /><br />As a Marketplace user you can post your announcements on our marketplace.</span></span></p>'
            ]
        );
        Cache::forget('setting_' . 'marketplace_user_welcome_text');
        Cache::forget('setting_' . 'marketplace_user_tutorial_text');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('core_settings')->where(['name'  => 'marketplace_user_welcome_text'])->delete();
        DB::table('core_settings')->where(['name'  => 'marketplace_user_tutorial_text'])->delete();
    }
}

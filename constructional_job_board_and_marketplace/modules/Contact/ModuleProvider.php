<?php

namespace Modules\Contact;

use Illuminate\Support\Facades\Auth;
use Modules\ModuleServiceProvider;

class ModuleProvider extends ModuleServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouterServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    public static function getTemplateBlocks()
    {
        return [
            'contact_block' => "\\Modules\\Contact\\Blocks\\Contact",
        ];
    }

    public static function getAdminMenu()
    {
        return [
            'help' => [
                "position" => 24,
                'url' => route('contact.admin.index'),
                'title' => __("Help"),
                'icon' => 'ri-question-line',
            ]
        ];
    }
}

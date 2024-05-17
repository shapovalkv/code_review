<?php
namespace Modules\User;
use App\Enums\UserPermissionEnum;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Modules\Gig\Models\Gig;
use Modules\ModuleServiceProvider;
use Modules\Payout\Models\VendorPayout;
use Modules\User\Models\Plan;
use Modules\User\Models\Support\Ticket;
use Modules\User\Services\Chat\MessageNotificationService;
use Modules\Vendor\Models\VendorRequest;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(){

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        Blade::directive('has_permission', function ($expression) {
            return "<?php if(auth()->user()->hasPermission({$expression})): ?>";
        });
        Blade::directive('end_has_permission', function ($expression) {
            return "<?php endif; ?>";
        });

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

    public static function getBookableServices()
    {
        return ['plan'=>Plan::class];
    }

    public static function getAdminMenu()
    {
        $noti_verify = User::countVerifyRequest();
        $noti = $noti_verify;
        $countNewSupport = \Modules\User\Models\Support\Ticket::query()->whereIn('status', [\Modules\User\Models\Support\Ticket::NEW, \Modules\User\Models\Support\Ticket::WAITING])->count();

        $options = [
            "position"=>100,
            'url'        => 'admin/module/user',
            'title'      => __('Users :count',['count'=>$noti ? sprintf('<span class="badge badge-warning">%d</span>',$noti) : '']),
            'icon'       => 'icon ion-ios-contacts',
            'permission' => 'user_manage',
            'children'   => [
                'user'=>[
                    'url'   => 'admin/module/user',
                    'title' => __('All Users')
                ],
                'role'=>[
                    'url'        => 'admin/module/user/role',
                    'title'      => __('Role Manager'),
                    'permission' => 'role_manage'
                ],
                'mailing'=>[
                    'url'        => 'admin/module/user/mailing',
                    'title'      => __('Mailing'),
                    'permission' => 'mailing_manage'
                ],
                'notices'=>[
                    'url'        => route('user.admin.notice.index'),
                    'title'      => __('Notices'),
                    'permission' => 'dashboard_notice_manage'
                ],
                'subscriber'=>[
                    'url'        => 'admin/module/user/subscriber',
                    'title'      => __('Subscribers'),
                    'permission' => 'newsletter_manage',
                ],
                'company_request'=>[
                    'url'        => route('user.admin.upgrade'),
                    'title'      => __("Company Request"),
                    'permission' => 'user_manage'
                ]
            ]
        ];
        return [
            'users'=> $options,
            'support'=>[
                "position"=>100,
                'url'        => route('user.admin.support.index'),
                'title'      => __('Support :count', ['count'=> $countNewSupport ? sprintf('<span class="badge badge-warning">%d</span>',$countNewSupport) : '']),
                'icon'       => 'fa fa-life-ring',
                'permission' => 'support_manage',
            ],
            'plan'=>[
                "position"=>50,
                'url'        => route('user.admin.plan.index'),
                'title'      => __('User Plans'),
                'icon'       => 'icon ion-ios-contacts',
                'permission' => 'user_manage',
                'children'   => [
                    'user-plan'=>[
                        'url'   => route('user.admin.plan.index'),
                        'title' => __('User Plans'),
                        'permission' => 'user_manage',
                    ],
                    'promocodes'=>[
                        'url'   => route('user.admin.promocode.index'),
                        'title' => __('Promo Codes'),
                        'permission' => 'user_manage',
                    ],
                    'plan-report'=>[
                        'url'        => route('user.admin.plan_report.index'),
                        'title'      => __('Plan Report'),
                        'permission' => 'user_manage',
                    ],
                    'employer-plan'=>[
                        'url'        => route('user.admin.plan_log.index'),
                        'title'      => __('Plan Log'),
                        'permission' => 'user_manage',
                    ],

                ]
            ]
        ];
    }

    public static function getUserFrontendMenu()
    {
        $configs = [
        ];
        return $configs;
    }

    public static function getUserMenu()
    {
        /**
         * @var $user User
         */
        $res = [

            'user_dashboard' => [
                'url' => 'user/dashboard',
                'title' => __("Dashboard"),
                'icon' => 'la la-home',
                'position' => 10
            ],
//            'my_orders' => [
//                'url' => 'user/order',
//                'title' => __("My Orders"),
//                'icon' => 'la la-luggage-cart',
////                'permission' => 'employer_manage',
//                'enable' => false
//            ],
//            'my_contact' => [
//                'url' => 'user/my-contact',
//                'title' => __("My Contact"),
//                'icon' => 'la la-envelope',
//                'enable' => false
//            ],
            'change_password' => [
                'url' => 'user/profile/change-password',
                'title' => __("Change Password"),
                'icon' => 'la la-lock',
                'enable' => true
            ],
        ];

        if (is_employer() || is_candidate() || is_marketplace_user() || is_employee()) {
            $res['user_saved_search'] = [
                'url'    => route('user.search-params.index'),
                'title'  => __("Saved Search Parameters"),
                'icon'   => 'las la-tasks',
                'enable' => true
            ];
        }

            $unreadMessages = app()->make(MessageNotificationService::class)->unreadCount(auth()->user()->id);
            $res['chat'] = [
                'url'    => route('user.chat.index'),
                'title'  => __("Messaging") . ($unreadMessages ? '<span class="count wishlist_count text-center">'.$unreadMessages.'</span>' : ''),
                'icon'   => 'fa fa-comment-dots',
                'enable' => true,
                'position' => 50,
                'permission' => ['candidate_manage', 'employer_manage', UserPermissionEnum::COMPANY_MESSAGING]
            ];
            $res['tutorial'] = [
                'url'    => route('user.tutorial'),
                'title'  => __("Tutorial Video"),
                'icon'   => 'la la-video',
                'enable' => true,
                'permission' => ['candidate_manage', 'employer_manage']
            ];

        $answeredTickets = Ticket::query()->where('user_id', auth()->user()->id)->where('status', Ticket::ANSWERED)->count();

        $res['support'] = [
            'url'    => route('user.support.index'),
            'title'  => __("Support")  . ($answeredTickets ? '<span class="count wishlist_count text-center">'.$answeredTickets.'</span>' : ''),
            'icon'   => 'las la-life-ring',
            'enable' => true
        ];

        return $res;
    }
}

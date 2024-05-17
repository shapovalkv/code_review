<?php
namespace Modules\User;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Modules\Equipment\Models\Equipment;
use Modules\Gig\Models\Gig;
use Modules\ModuleServiceProvider;
use Modules\Payout\Models\VendorPayout;
use Modules\User\Models\Plan;
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
                'subscriber'=>[
                    'url'        => 'admin/module/user/subscriber',
                    'title'      => __('Subscribers'),
                    'permission' => 'newsletter_manage',
                ],
            ]
        ];
        return [
            'users'=> $options,
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
                    'employer-plan'=>[
                        'url'        => route('user.admin.plan_report.index'),
                        'title'      => __('Plan Report'),
                        'permission' => 'user_manage',
                    ],
                ]
            ]
        ];
    }

    public static function getUserFrontendMenu()
    {
        $configs = [
            'user_dashboard' => [
                'url' => route('user.dashboard'),
                'title' => __("Dashboard"),
                'icon' => 'ri-dashboard-line',
                'enable' => true,
                'order' => 1,
            ],
            'seller_dashboard' => [
                'url' => route('user.dashboard'),
                'title' => __("Seller Dashboard"),
                'icon' => 'la la-home',
                'permission' => 'candidate_manage',
                'enable' => Gig::isEnable()
            ],
            'seller_gigs' => [
                'url' => 'seller/all-gigs',
                'title' => __("Gigs"),
                'icon' => 'la la-briefcase',
                'permission' => 'candidate_manage',
                'enable' => Gig::isEnable()
            ],
            'seller_equipments' => [
                'url' => route('seller.all.equipments'),
                'title' => __("Equipment"),
                'icon' => 'ri-car-line',
                'permission' => 'employer_manage',
                'enable' => Equipment::isEnable(),
                'order' => 4
            ],
            'candidate_equipments' => [
                'url' => route('seller.all.equipments'),
                'title' => __("My Equipment"),
                'icon' => 'ri-car-line',
                'permission' => 'candidate_manage',
                'enable' => Equipment::isEnable() && !is_admin(),
                'order' => 4
            ],
            'seller_order' => [
                'url' => 'seller/orders',
                'title' => __("Gig Orders"),
                'icon' => 'la la-luggage-cart',
                'permission' => 'candidate_manage',
                'enable' => Gig::isEnable()
            ],
            'buyer_order' => [
                'url' => 'buyer/orders',
                'title' => __("Gig Orders"),
                'icon' => 'la la-luggage-cart',
                'permission' => 'employer_manage',
                'enable' => Gig::isEnable() && !is_admin()
            ],
            'payout' => [
                'url' => 'user/payout',
                'title' => __("Payouts"),
                'icon' => 'las la-credit-card',
                'permission' => 'candidate_payout_manage',
                'enable' => VendorPayout::isEnable() && Gig::isEnable()
            ],

            'user_profile' => [
                'url' => route('user.profile.index'),
                'title' => __("My Profile"),
                'icon' => 'la la-user-tie',
                'enable' => false
            ],
            'company_profile' => [
                'url' => route('user.company.profile'),
                'title' => __("Company settings"),
                'icon' => 'ri-settings-3-line',
                'permission' => 'employer_manage',
                'enable' => true,
                'under_divider' => true,
                'order' => 1
            ],
            'candidate_profile' => [
                'url' => route('user.profile.index'),
                'title' => __("Candidate profile"),
                'icon' => 'ri-user-line',
                'permission' => 'candidate_manage',
                'enable' => (!is_admin() && !is_employer()),
                'under_divider' => true,
                'order' => 1
            ],
            'new_job' => [
                'url' => route('user.create.job'),
                'title' => __("Post A New Job"),
                'icon' => 'la la-paper-plane',
                'permission' => 'employer_manage',
                'enable' => false
            ],
            'manage_jobs' => [
                'url' => route('user.all.jobs'),
                'title' => __("My Jobs"),
                'icon' => 'ri-briefcase-line',
                'permission' => 'employer_manage',
                'enable' => true,
                'order' => 2
            ],
            'my_orders' => [
                'url' => 'user/order',
                'title' => __("My Orders"),
                'icon' => 'la la-luggage-cart',
                'permission' => 'employer_manage',
                'enable' => false
            ],
            'all_applicants' => [
                'url' => route('user.applicants'),
                'title' => __("Applicants"),
                'icon' => 'ri-user-line',
                'permission' => 'employer_manage',
                'enable' => true,
                'order' => 3
            ],
            'applied_jobs' => [
                'url' => route('user.applied_jobs'),
                'title' => __("My Jobs"),
                'icon' => 'ri-briefcase-line',
                'permission' => 'candidate_manage',
                'enable' => !is_admin(),
                'order' => 2,
            ],
            'user_bookmark' => [
                'url' => route('user.wishList.index'),
                'title' => __("Bookmarks"),
                'icon' => 'ri-bookmark-line',
                'permission' => 'candidate_manage',
                'enable' => !is_admin(),
                'order' => 8,
            ],
            'user_bookmark_employer' => [
                'url' => route('user.wishList.index'),
                'title' => __("Bookmarks"),
                'icon' => 'ri-bookmark-line',
                'permission' => 'employer_manage',
                'enable' => true,
                'order' => 6
            ],
            'following_employers' => [
                'url' => 'user/following-employers',
                'title' => __("Following Employers"),
                'icon' => 'la la-user',
                'permission' => 'candidate_manage',
                'enable' => false
            ],
            'my_plan' => [
                'url' => route('user.plan'),
                'title' => __("Pricing Plans"),
                'icon' => 'ri-dvd-line',
                'permission' => 'employer_manage',
                'enable' => true,
                'under_divider' => true,
                'order' => 2
            ],
            'user_orders' => [
                'url' => route('user.order'),
                'title' => __("Payments history"),
                'icon' => 'ri-copper-coin-line',
                'enable' => !is_admin(),
                'under_divider' => true,
                'order' => 3,
                'is_sub_item' => true
            ],
            'help' => [
                'url' => route('user.help.index'),
                'title' => __("Help"),
                'icon' => 'ri-question-line',
                'enable' => true,
                'under_divider' => true,
                'order' => 4
            ],
            'my_contact' => [
                'url' => route('user.chat.index'),
                'title' => __("Messages"),
                'icon' => 'ri-message-2-line',
                'permission' => 'employer_manage',
                'enable' => true,
                'order' => 5
            ],
            'candidate_contact' => [
                'url' => route('user.chat.index'),
                'title' => __("Messages"),
                'icon' => 'ri-message-2-line',
                'permission' => 'candidate_manage',
                'enable' => (!is_admin() && !is_employer()),
                'order' => 5
            ],
            'change_password' => [
                'url' => 'user/profile/change-password',
                'title' => __("Change Password"),
                'icon' => 'la la-lock',
                'enable' => false
            ],
            'user_logout' => [
                'url' => 'user/logout',
                'title' => __("Logout"),
                'icon' => 'la la-sign-out',
                'enable' => false,
            ]
        ];

        return collect($configs)->sortBy('order')->toArray();
    }

    public static function getUserMenu()
    {
        /**
         * @var $user User
         */
        $res = [];
        $user = Auth::user();

        $is_wallet_module_disable = setting_item('wallet_module_disable');
        if(empty($is_wallet_module_disable))
        {
            $res['wallet']= [
                'position'   => 27,
                'icon'       => 'fa fa-money',
                'url'        => route('user.wallet'),
                'title'      => __("My Wallet"),
            ];
        }

        if(setting_item('inbox_enable')) {
            $res['chat'] = [
                'position' => 20,
                'icon' => 'fa fa-comments',
                'url' => route('user.chat'),
                'title' => __("Messages"),
            ];
        }

        return $res;
    }
}

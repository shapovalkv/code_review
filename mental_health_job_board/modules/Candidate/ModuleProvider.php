<?php
namespace Modules\Candidate;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Modules\Candidate\EventServiceProvider;
use Modules\ModuleServiceProvider;


class ModuleProvider extends ModuleServiceProvider
{

    public function boot(){
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        $this->publishes([
            __DIR__.'/Config/config.php' => config_path('candidate.php'),
        ]);

    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/config.php', 'candidate'
        );

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

    }

    public static function getAdminMenu()
    {
        $candidate_menus = [
            'candidate'=>[
                "position"=>26,
                'url'        => route('candidate.admin.index'),
                'title'      => __("Candidate"),
                'icon'       => 'ion-md-bookmarks',
                'permission' => 'candidate_manage_others',
                'children'   => [
                    'candidates_view'=>[
                        'url'        => route('candidate.admin.index'),
                        'title'      => __("All Candidates"),
                        'permission' => 'candidate_manage',
                    ],
                    'candidates_create'=>[
                        'url'        => route('user.admin.create', ['candidate_create' => 1]),
                        'title'      => __("Add Candidate"),
                        'permission' => 'candidate_manage',
                    ],
                    'category'=>[
                        "position"=> 29,
                        'url'        => route('candidate.admin.category.index'),
                        'title'      => __("Category"),
                        'permission' => 'category_manage_others'
                    ]
                ]
            ]

        ];
        if(Auth::check()){
            if(Auth::user()->hasPermission('candidate_manage') && !Auth::user()->hasPermission('candidate_manage_others')){
                $candidate_menus['candidate_my_applied'] = [
                    "position"=> 27,
                    'url'        => route('candidate.admin.myApplied'),
                    'title'      => __("My Applied"),
                    'icon'       => 'ion-md-bookmarks',
                    'permission' => 'candidate_manage'
                ];
            }
        }
        return $candidate_menus;
    }

    public static function getTemplateBlocks(){
        return [
            'list_candidates'=>"\\Modules\\Candidate\\Blocks\\ListCandidates",
        ];
    }

    public static function getUserMenu()
    {
        $res = [];
        if(is_candidate()) {

            $res['user_profile'] = [
                'url' => '#',
                'title' => __("Candidate"),
                'icon' => 'la la-user-tie',
                'enable' => true,
                'position'=>20,
                'children'=>[
                    'profile'=>[
                        'url' => route('user.candidate.index'),
                        'title' => __("Profile"),
                        'icon' => 'la la-user-tie',
                        'position' => 10
                    ],
                    'cv_manager' => [
                        'url' => 'user/cv-manager',
                        'title' => __("Resume/ CV Manager"),
                        'icon' => 'la la-file-invoice',
                        'permission' => 'candidate_manage',
                    ],
//                    'following_employers' => [
//                        'url' => 'user/following-employers',
//                        'title' => __("Following"),
//                        'icon' => 'la la-user',
//                        'permission' => 'candidate_manage',
//                    ],
                ]
            ];
            $res['user_profile']['children']['applied_jobs'] = [
                'url' => 'user/applied-jobs',
                'title' => __("Applied Jobs"),
                'icon' => 'la la-user-friends',
                'permission' => 'candidate_manage',
            ];
            $res['user_profile']['children']['user_bookmark'] =[
                'url' => 'user/bookmark',
                'title' => __("Bookmark Jobs"),
                'icon' => 'la la-bookmark-o',
                'permission' => 'candidate_manage',
            ];
        }

        return $res;
    }
}

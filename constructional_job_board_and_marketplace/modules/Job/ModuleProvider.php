<?php
namespace Modules\Job;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Helpers\SitemapHelper;
use Modules\Job\Models\Job;
use Modules\ModuleServiceProvider;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(SitemapHelper $sitemapHelper){
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        if(is_installed()){
            $sitemapHelper->add("job",[app()->make(Job::class),'getForSitemap']);
        }
    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    public static function getAdminMenu()
    {
        return [
            'job'=>[
                "position" => 24,
                'url'        => 'admin/module/job',
                'title'      => __("Job"),
                'icon'       => 'ion-ios-briefcase',
                'permission' => 'job_manage',
                'children'   => [
                    'job_view'=>[
                        'url'        => 'admin/module/job',
                        'title'      => __("All Jobs"),
                        'permission' => 'job_manage',
                    ],
                    'job_create'=>[
                        'url'        => 'admin/module/job/create',
                        'title'      => __("Add Job"),
                        'permission' => 'job_manage',
                    ],
                    'job_type'=>[
                        'url'        => 'admin/module/job/job-type',
                        'title'      => __("Job Types"),
                        'permission' => 'job_manage_others',
                    ],
                    'job_category'=>[
                        'url'        => route('job.admin.category.index'),
                        'title'      => __("Category"),
                        'permission' => 'job_manage_others'
                    ],
                ]
            ],
            'job_type'=>[
                'url'        => 'admin/module/job/all-applicants',
                'title'      => __("Applicants"),
                'permission' => 'job_manage',
                "position" => 25,
                'icon'       => 'ion-ios-briefcase',
                'children'   => [
                    'all_applicant_view'=>[
                        'url'        => 'admin/module/job/all-applicants',
                        'title'      => __("All Applicants"),
                        'permission' => 'job_manage',
                    ],
                    'all_applicant_create'=>[
                        'url'        => 'admin/module/job/all-applicants/create',
                        'title'      => __("Add Applicant"),
                        'permission' => 'job_manage',
                    ],
                ]
            ]

        ];
    }

    public static function getTemplateBlocks(){
        return [
            'popular_job_categories' => "\\Modules\\Job\\Blocks\\PopularJobCategories",
            'featured_jobs' => "\\Modules\\Job\\Blocks\\FeaturedJobs"
        ];
    }
}

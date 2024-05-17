<?php
namespace Modules\Job;

use App\Enums\UserPermissionEnum;
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
        $noti_verify = Job::countVerifyRequest();
        $noti = $noti_verify;

        return [
            'job'=>[
                "position" => 24,
                'url'        => 'admin/module/job',
                'title'      => setting_item('job_need_approve') ? __("Job :count",['count'=>$noti ? sprintf('<span class="badge badge-warning">%d</span>',$noti) : '']) : __("Job") ,
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
                    'job_position'=>[
                        'url'        => route('job.admin.position.index'),
                        'title'      => __("Position"),
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
            'job_categories' => "\\Modules\\Job\\Blocks\\JobCategories",
            'jobs_list' => "\\Modules\\Job\\Blocks\\JobsList"
        ];
    }

    public static function getUserMenu()
    {
        $res = [
            'company_profile' => [
                'url' => 'user/company/profile',
                'title' => __("Company Profile"),
                'icon' => 'la la-user-tie',
                'permission' => ['employer_manage'],
                'position'=>20,
            ],
            'manage_jobs' => [
                'url' => '#',
                'title' => __("Manage Jobs"),
                'icon' => 'la la-briefcase',
                'permission' => ['employer_manage', UserPermissionEnum::COMPANY_JOB_MANAGE],
                'position'=>30,
                'children'=>[
                    'jobs'=>[
                        'url' => 'user/manage-jobs',
                        'title' => __("All Jobs"),
                        'icon' => 'la la-briefcase',
                        'permission' => ['employer_manage', UserPermissionEnum::COMPANY_JOB_MANAGE],
                    ],
                    'new_job' => [
                        'url' => 'user/new-job',
                        'title' => __("Post a Job"),
                        'icon' => 'la la-paper-plane',
                        'permission' => ['employer_manage', UserPermissionEnum::COMPANY_JOB_MANAGE],
                        'enable' => true
                    ],
                    'all_applicants' => [
                        'url' => 'user/applicants',
                        'title' => __("All Applicants"),
                        'icon' => 'la la-user-friends',
                        'permission' => ['employer_manage', UserPermissionEnum::COMPANY_JOB_MANAGE],
                        'enable' => true
                    ],
                ]
            ],
        ];
        return $res;
    }
}

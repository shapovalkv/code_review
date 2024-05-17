<?php

namespace Modules\Job\Models;

use App\BaseModel;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Modules\Candidate\Models\Candidate;
use Modules\Candidate\Models\CandidateCvs;
use Modules\Company\Models\Company;

class JobCandidate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'bc_job_candidates';
    protected $fillable = [
        'job_id',
        'candidate_id',
        'company_id',
        'cv_id',
        'message',
        'status'
    ];

    public function jobInfo()
    {
        return $this->hasOne(Job::class, "id", 'job_id');
    }

    public function candidateInfo()
    {
        return $this->hasOne(Candidate::class, "id", 'candidate_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, "id", 'candidate_id');
    }

    public function cvInfo()
    {
        return $this->hasOne(CandidateCvs::class, "id", 'cv_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public static function search(Request $request, $companyId = null, $candidateId = null)
    {
        $modelJobCandidate = parent::query()->select("bc_job_candidates.*")
            ->where('bc_job_candidates.status', '!=', 'delete');

        if (!empty($companyId)) {
            $modelJobCandidate->where('bc_job_candidates.company_id', $companyId);
        }

        if (!empty($candidateId)) {
            $modelJobCandidate->where('candidate_id', $candidateId);
        }

        if ($statuses = $request->query('status')) {
            $modelJobCandidate->whereIn("bc_job_candidates.status", [$statuses]);
        }

        $categorySlug = $request->query('category') ?? $request->category;
        if (!empty($categorySlug)) {
            $modelJobCandidate->join('bc_jobs', 'bc_job_candidates.job_id', '=', 'bc_jobs.id')
                ->join('bc_categories', 'bc_jobs.category_id', '=', 'bc_categories.id')
                ->whereIn('bc_categories.slug', $categorySlug);
        }

        if (!empty($datePosted = $request->query('date'))) {
            switch ($datePosted) {
                case 'last_hour':
                    $date_p = date('Y-m-d H:i:s', strtotime('-1 hour'));
                    break;
                case 'last_1':
                    $date_p = date('Y-m-d H:i:s', strtotime("-1 day"));
                    break;
                case 'last_7':
                    $date_p = date('Y-m-d H:i:s', strtotime("-1 week"));
                    break;
                case 'last_14':
                    $date_p = date('Y-m-d H:i:s', strtotime("-2 weeks"));
                    break;
                case 'last_30':
                    $date_p = date('Y-m-d H:i:s', strtotime("-1 month"));
                    break;
                case 'range':
                    $dateRange = $request->query('date_posted');
                    $dateFrom = date('Y-m-d', $dateRange['dateFrom']);
                    $dateTo = date('Y-m-d', $dateRange['dateTo']);
            }
            if (!empty($date_p)) {
                $modelJobCandidate->where('bc_job_candidates.created_at', '>=', $date_p);
            }

            if (!empty($dateFrom) && !empty($dateTo)) {
                $modelJobCandidate->whereBetween('bc_job_candidates.created_at', [$dateFrom, $dateTo]);
            }
        }

        if (!empty($jobName = $request->query("keywords"))) {
            if (setting_item('site_enable_multi_lang') && setting_item('site_locale') != app()->getLocale()) {
                $modelJobCandidate->join('bc_jobs', 'bc_job_candidates.job_id', '=', 'bc_jobs.id')
                    ->leftJoin('bc_job_translations', function ($join) {
                        $join->on('bc_jobs.id', '=', 'bc_job_translations.origin_id');
                    });
                $modelJobCandidate->where('bc_job_translations.title', 'LIKE', '%' . $jobName . '%');
            } else {
                $modelJobCandidate->join('bc_jobs', 'bc_job_candidates.job_id', '=', 'bc_jobs.id')
                    ->where('bc_jobs.status', '=', 'publish')
                    ->where('bc_jobs.title', 'LIKE', '%' . $jobName . '%');
            }
        }

        if (!empty($orderby = $request->query("orderby"))) {
            switch ($orderby) {
                case"new":
                    $modelJobCandidate->orderBy("bc_job_candidates.id", "desc");
                    break;
                case"old":
                    $modelJobCandidate->orderBy("bc_job_candidates.id", "asc");
                    break;
                case"name_high":
                    $modelJobCandidate->orderBy("bc_job_candidates.title", "asc");
                    break;
                case"name_low":
                    $modelJobCandidate->orderBy("bc_job_candidates.title", "desc");
                    break;
                default:
                    $modelJobCandidate->orderBy("bc_job_candidates.id", "desc");
                    break;
            }
        } else {
            $modelJobCandidate->orderBy("bc_job_candidates.id", "desc");
        }

        $modelJobCandidate->groupBy("bc_job_candidates.id");

        if ($request->query("count")) {
            return $modelJobCandidate->count();
        }

        $limit = $request->query('limit', 10);

        return $modelJobCandidate->with(['author', 'jobInfo', 'candidateInfo', 'cvInfo', 'company', 'company.getAuthor'])->paginate($limit);
    }
}

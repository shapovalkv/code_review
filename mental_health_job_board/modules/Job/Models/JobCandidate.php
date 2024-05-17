<?php
namespace Modules\Job\Models;

use App\BaseModel;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Candidate\Models\Candidate;
use Modules\Candidate\Models\CandidateCvs;
use Modules\Company\Models\Company;

class JobCandidate extends BaseModel
{
    use SoftDeletes;

    public const RELATION_USER = 'user';

    protected $table = 'bc_job_candidates';
    protected $fillable = [
        'job_id',
        'candidate_id',
        'company_id',
        'cv_id',
        'message',
        'status',
        'initiator_id'
    ];

    const APPROVED_STATUS = 'approved';
    const PENDING_STATUS = 'pending';
    const REJECTED_STATUS = 'rejected';
    const APPLIED_STATUS = 'applied';

    public function jobInfo()
    {
        return $this->belongsTo(Job::class,  'job_id');
    }

    public function candidateInfo()
    {
        return $this->belongsTo(Candidate::class,  'candidate_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, "candidate_id");
    }

    public function cvInfo()
    {
        return $this->belongsTo(CandidateCvs::class,  'cv_id');
    }

    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }
}

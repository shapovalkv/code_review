<?php
namespace Modules\User\Models;
use App\BaseModel;
use Modules\Candidate\Models\Candidate;
use Modules\Company\Models\Company;
use Modules\Job\Models\Job;

class UserWishList extends BaseModel
{
    protected $table = 'user_wishlist';
    protected $fillable = [
        'object_id',
        'object_model',
        'user_id'
    ];

    public function service()
    {
        $allServices = [
            'candidate'=>Candidate::class,
            'company'=>Company::class,
            'job'=>Job::class,
        ];
        $module = $allServices[$this->object_model];
        return $this->hasOne($module, "id", 'object_id')->where("deleted_at",null);
    }
    public function job(){
        return $this->hasOne(Job::class, "id", 'object_id')->where('object_model', 'job')->where("deleted_at",null);
    }
    public function company(){
        return $this->hasOne(Company::class, "id", 'object_id')->where('object_model', 'company')->where("deleted_at",null);
    }
    public function candidate(){
        return $this->hasOne(Candidate::class, "id", 'object_id')->where('object_model', 'candidate')->where("deleted_at",null);
    }
}

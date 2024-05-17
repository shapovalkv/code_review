<?php


namespace Modules\User\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Booking\Models\Bookable;

class Plan extends Bookable
{

    use SoftDeletes;

    protected $table = 'bc_plans';
    public $type = 'plan';

    const TYPE_RECURRING = 'recurring';
    const TYPE_ONE_TIME  = 'one_time';

    const PLAN_JOB_SPONSORED = 'Promote job';
    const PLAN_EQUIPMENT_SPONSORED = 'Promote equipment';

    public function getDurationTextAttribute(){
        $html = '';
        switch ($this->duration_type){
            case "day":
                if($this->duration <= 1)
                $html = __(":duration day",['duration'=>$this->duration]);
                else
                $html = __(":duration days",['duration'=>$this->duration]);
            break;
            case "week":
                if($this->duration <= 1)
                $html = __(":duration week",['duration'=>$this->duration]);
                else
                $html = __(":duration weeks",['duration'=>$this->duration]);
            break;
            case "month":
                if($this->duration <= 1)
                $html = __(":duration month",['duration'=>$this->duration]);
                else
                $html = __(":duration months",['duration'=>$this->duration]);
            break;
            case "year":
                if($this->duration <= 1)
                $html = __(":duration year",['duration'=>$this->duration]);
                else
                $html = __(":duration years",['duration'=>$this->duration]);
            break;
        }
        return $html;
    }
    public function getDurationTypeTextAttribute(){
        switch ($this->duration_type){
            case "day":
                return __("day");
            break;
            case "week":
                return __("week");
            break;
            case "month":
                return __("month");
            break;
            case "year":
                return __("year");
            break;
        }
    }

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }

    public function features()
    {
        return $this->hasMany(PlanFeature::class,'plan_id');
    }

    public function isOneTime()
    {
        return $this->plan_type === self::TYPE_ONE_TIME;
    }

    public function scopeVisible($query)
    {
        return $query->where('is_hidden', 0)->where('status', 'publish');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role_id', $role->id);
    }

    public function hasFeature(string $slug): bool
    {
        return $this->features->where('slug', $slug)->count() > 0;
    }

    public function getFeatureBySlug(string $slug): PlanFeature
    {
        $feature = $this->features->where('slug', $slug)->first();
            return $feature ?: new PlanFeature();
    }

    public function storeFeatures(array $data)
    {
        foreach ($data as $slug => $value) {
            if (empty($value['is_active'])) {
                continue;
            }
            $this->features()->updateOrCreate(['slug' => $slug], [
                'name' => $slug,
                'slug' => $slug,
                'value' => $value['value'] ?? ''
            ]);
        }
    }

    public function apply($user, $params = [])
    {
        if (isset($params['model']) && isset($params['model_id'])) {
            $klass = $params['model'];
            $action = $params['action'] ?? 'none';
            $entity = $klass::find($params['model_id']);
            if ($entity && method_exists($entity, $action)) {
                $entity->$action($params);
            }
        }
    }
}

<?php

namespace Modules\User\Models;

use App\BaseModel;
use Illuminate\Http\Request;
use Modules\Candidate\Models\Candidate;
use Modules\Company\Models\Company;
use Modules\Equipment\Models\Equipment;
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
            'candidate' => Candidate::class,
            'company' => Company::class,
            'job' => Job::class,
            'equipment' => Equipment::class
        ];
        $module = $allServices[$this->object_model];
        return $this->hasOne($module, "id", 'object_id')->where("deleted_at", null);
    }

    public function job()
    {
        return $this->hasOne(Job::class, "id", 'object_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, "id", 'object_id');
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class, "id", 'object_id');
    }

    public function equipment()
    {
        return $this->hasOne(Equipment::class, "id", 'object_id');
    }

    public static function search(Request $request, $user = null)
    {
        $type = $request->get('active');

        $modelWishList = parent::query()->select("user_wishlist.*")
            ->where('user_wishlist.user_id', $user)
            ->where('user_wishlist.create_user', $user);

        $modelTable = null;
        if ($type) {
            switch ($type) {
                case 'candidate':
                    $modelTable = (new Candidate())->getTable();
                    break;
                case 'job':
                    $modelTable = (new Job())->getTable();
                    break;
                case 'equipment':
                    $modelTable = (new Equipment())->getTable();
                    break;
                case 'company':
                    $modelTable = (new Company())->getTable();
                    break;
            }
        }

        if ($modelTable && !empty($wishlistName = $request->query('keywords'))) {
            $modelWishList->join($modelTable, 'user_wishlist.object_id', '=', $modelTable.'.id');
            if ($type === 'company') {
                $modelWishList->where($modelTable.'.name', 'LIKE', '%' . $wishlistName . '%');
            } else {
                $modelWishList->where($modelTable.'.title', 'LIKE', '%' . $wishlistName . '%');
            }
        }

        $orderBy = $request->query("orderby");
        switch ($orderBy) {
            case "new":
                $modelWishList->orderBy("user_wishlist.id", "desc");
                break;
            case "old":
                $modelWishList->orderBy("user_wishlist.id", "asc");
                break;
            case "name_high":
                $modelWishList->orderBy("user_wishlist.title", "asc");
                break;
            case "name_low":
                $modelWishList->orderBy("user_wishlist.title", "desc");
                break;
            default:
                $modelWishList->orderBy("user_wishlist.id", "desc");
                break;
        }

        if (!empty($datePosted = $request->query('date_posted'))) {
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
                $modelWishList->where('user_wishlist.created_at', '>=', $date_p);
            }

            if (!empty($dateFrom) && !empty($dateTo)) {
                $modelWishList->whereBetween('user_wishlist.created_at', [$dateFrom, $dateTo]);
            }
        }

        $modelWishList->groupBy("user_wishlist.id");

        if ($request->query("count")) {
            return $modelWishList->count();
        }

        if ($type = $request->get('active')) {
            $modelWishList->where('object_model', $type)->with([$type]);
        }
        return $modelWishList->paginate($request->query('limit', 10));
    }
}

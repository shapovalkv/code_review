<?php
namespace Modules\Company\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Location\Models\Location;

/**
 * @property Company company
 */
class CompanyOffices extends BaseModel
{
    protected $table = 'bc_company_offices_locations';
    protected $fillable = [
        'company_id',
        'location_id',
        'is_main',
        'map_lat',
        'map_lng',
        'map_zoom'
    ];
    protected $create_user = null;

    public function location()
    {
        return $this->hasOne(Location::class, 'id', 'location_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}

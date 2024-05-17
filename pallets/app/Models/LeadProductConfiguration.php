<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadProductConfiguration extends Model
{
    use HasFactory;

    protected $table = 'lead_product_configurations';

    const PRODUCT_MIN_LENGTH = 100;
    const PRODUCT_MAX_LENGTH = 600;

    const PRODUCT_MIN_WIDTH = 100;
    const PRODUCT_MAX_WIDTH = 400;

    const PRODUCT_MIN_HEIGHT = 0.1;
    const PRODUCT_MAX_HEIGHT = 400;

    const PRODUCT_MIN_WEIGHT = 1;
    const PRODUCT_MAX_WEIGHT = 100;

    const PRODUCT_MIN_INFEED_RATE = 1;
    const PRODUCT_MAX_INFEED_RATE = 15;

    const PALLET_MIN_LENGTH = 600;
    const PALLET_MAX_LENGTH = 1220;

    const PALLET_MIN_WIDTH = 600;
    const PALLET_MAX_WIDTH = 1220;

    const PALLET_MIN_HEIGHT = 0.1;
    const PALLET_MAX_HEIGHT = 200;

    const PRODUCT_BOX = 'box';
    const PRODUCT_BAG = 'bag';
    const PRODUCT_PAIL = 'pail';
    const PRODUCT_TOTE = 'tote';
    const PRODUCT_TRAY = 'tray';

    const SYSTEM_PALLET_MIN_HIGHT = 1;
    const SYSTEM_PALLET_MAX_HIGHT = 1800;

    const NO_INFЕЕD_EXCLUSIONS = 'No Infeed Exclusions';
    const LEFT_RIGHT_INFЕЕD_NOT_COMPATIBLE = 'Left & Right Infeed Not Compatible';

    const STATUS_DRAFT = 'draft';
    const STATUS_OVERVIEW = 'overview';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'lead_id',
        'gripper_id',
        'product_infeed_id',
        'replaced_product_infeed_id',
        'left_pallet_position_id',
        'replaced_left_pallet_position_id',
        'right_pallet_position_id',
        'replaced_right_pallet_position_id',
        'system_pallet_height',
        'product_name',
        'product_type_id',
        'product_length',
        'product_width',
        'product_height',
        'product_weight',
        'product_infeed_rate',
        'pallet_length',
        'pallet_width',
        'pallet_height',
        'robot_id',
        'request_customization',
        'total_price',
        'hs_custom_object_palletizer_id',
        'status',
    ];

    public function tool() : HasOne
    {
        return $this->hasOne(PalletizerModule::class, 'id', 'gripper_id');
    }

    public function infeedPosition() : HasOne
    {
        return $this->hasOne(PalletizerModule::class, 'id', 'product_infeed_id');
    }

    public function replacementInfeedPosition() : HasOne
    {
        return $this->hasOne(PalletizerModule::class, 'id', 'replaced_product_infeed_id');
    }

    public function leftPosition() : HasOne
    {
        return $this->hasOne(PalletizerModule::class, 'id', 'left_pallet_position_id');
    }

    public function replacementLeftPosition() : HasOne
    {
        return $this->hasOne(PalletizerModule::class, 'id', 'replaced_left_pallet_position_id');
    }

    public function rightPosition() : HasOne
    {
        return $this->hasOne(PalletizerModule::class, 'id', 'right_pallet_position_id');
    }

    public function replacementRightPosition() : HasOne
    {
        return $this->hasOne(PalletizerModule::class, 'id', 'replaced_right_pallet_position_id');
    }


    public function robot() : HasOne
    {
        return $this->hasOne(PalletizerModule::class, 'id', 'robot_id');
    }

    public function productType() : HasOne
    {
        return $this->hasOne(ProductType::class, 'id', 'product_type_id');
    }


}

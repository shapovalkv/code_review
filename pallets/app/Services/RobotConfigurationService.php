<?php

namespace App\Services;

use App\Helper;
use App\Models\Basic;
use App\Models\Lead;
use App\Models\LeadProductConfiguration;
use App\Models\PalletizerModule;
use App\Models\ProductType;
use App\Models\RobotDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;

class RobotConfigurationService extends Basic
{
    const PAYLOAD_SAFETY_FACTOR = 95;
    const REACH_SAFETY_FACTOR = 95;

    // Variables in 'inch'
    const INCH = 25.4;

    const FLOOR_PALLET_ZERO_X = 26.57  * self::INCH;
    const FLOOR_PALLET_ZERO_Y = 30.1  * self::INCH;
    const FLOOR_PALLET_ZERO_Z = 0;

    const CONVEYOR_PALLET_ZERO_X = 30.48  * self::INCH;
    const CONVEYOR_PALLET_ZERO_Y = 42.125  * self::INCH;
    const CONVEYOR_PALLET_ZERO_Z = 0;
    const CONVEYOR_PALLET_TOP = 20  * self::INCH;

    public function processingRobotCalculation($configuration, $eaot)
    {
        $floorPalletReachMath = $this->floorPalletReachMath($configuration, $eaot);
        $conveyorPalletReachMath = $this->conveyorPalletReachMath($configuration, $eaot);

        $robotCollection = collect();
        $robots = RobotDetail::all();
        foreach ($robots as $robot){
            if (!$robot->in_scope){
                continue;
            }

            $maxFlorReach = $this->maxFlorReach($robot->robot_base_height * self::INCH, $robot->reach_center_height * self::INCH, $floorPalletReachMath);
            $maxConveyorReach = $this->maxConveyorReach($robot->robot_base_height * self::INCH, $robot->reach_center_height * self::INCH, $conveyorPalletReachMath, $floorPalletReachMath);

            $numberProductPicked = $configuration->product_infeed_rate > 7.5 ? 2 : 1;

            $floorPallets = false;
            $conveyorPallets = false;

            if ($leftPosition = $configuration->leftPosition->palletConveyor) {
                $floorPallets = $leftPosition->is_pallet == true;
                $conveyorPallets = $leftPosition->is_conveyor == true;
            }

            if ($rightPosition = $configuration->rightPosition->palletConveyor) {
                $floorPallets = $floorPallets || ($rightPosition->is_pallet == true);
                $conveyorPallets = $conveyorPallets || ($rightPosition->is_conveyor == true);
            }

            $robotPayload = $robot->payload_weight > $this->RobotPayloadRequired($eaot->weight, $configuration->product_weight, $numberProductPicked);
            $robotReach = $robot->reach_distance > $this->reachRequired($maxFlorReach, $maxConveyorReach, $floorPallets, $conveyorPallets);


            if ($robotPayload && $robotReach) {
                $robotCollection->push($robot);
            } else continue;
        }


        return $robotCollection;
    }

    public function RobotPayloadRequired($EOATWeight, $productWeight, $numberProductPicked)
    {
        return ($EOATWeight + $productWeight * $numberProductPicked) / ($this::PAYLOAD_SAFETY_FACTOR / 100);
    }

    public function maxX($palletZero, $productWidth)
    {
        return $palletZero - ($productWidth / 2);
    }

    public function minX($palletZero, $productWidth, $palletLength)
    {
        return $palletZero - $palletLength - ($productWidth / 2);
    }

    public function minY($palletZero, $productWidth)
    {
        return $palletZero  + ($productWidth / 2);
    }

    public function maxY($palletWidth, $palletZero, $productWidth, $eoatWidth)
    {
        return $palletZero + $palletWidth - ($productWidth / 2) + $eoatWidth;
    }

    public function furthestCorner($maxX, $minX)
    {
        return ($maxX > abs($minX)) ? $maxX : $minX;
    }

    public function highestZ($productHeight, $palletTopHeight, $eaotZDimensions, $conveyorTop = null)
    {
        $result = $productHeight + $palletTopHeight + $eaotZDimensions;
        if ($conveyorTop) {
            $result += $conveyorTop;
        }
        return $result;
    }

    public function lowestZ($productHeight, $palletHeight, $eaotZDimensions, $conveyorTop = null)
    {
        $result = $palletHeight + $productHeight + $eaotZDimensions;
        if ($conveyorTop) {
            $result += $conveyorTop;
        }
        return $result;
    }

    public function floorPalletReachMath($data, $eaot): array
    {
        $eaotYOffset = $eaot->name == 'Dual vacuum' ? $data->product_width / 2 : $eaot->y_offset * self::INCH;
        return [
            'pallet_zero' => [
                'x' => $this::FLOOR_PALLET_ZERO_X ,
                'y' => $this::FLOOR_PALLET_ZERO_Y ,
                'z' => $this::FLOOR_PALLET_ZERO_Z ,
            ],
            'pallet_top' => $data->pallet_height ,
            'max_x' => $this->maxX(self::FLOOR_PALLET_ZERO_X, $data->product_width) ,
            'min_x' => $this->minX(self::FLOOR_PALLET_ZERO_X, $data->product_width, $data->pallet_length) ,
            'max_y' => $this->maxY($data->pallet_width, self::FLOOR_PALLET_ZERO_Y, $data->product_width, $eaotYOffset) ,
            'min_y' => $this->minY(self::FLOOR_PALLET_ZERO_Y, $data->product_width) ,
            'furthestCorner' => [
                'x' => $this->furthestCorner($this->maxX(self::FLOOR_PALLET_ZERO_X, $data->product_width), $this->minX(self::FLOOR_PALLET_ZERO_X, $data->product_width, $data->pallet_length)) ,
                'y' => $this->maxY($data->pallet_width, self::FLOOR_PALLET_ZERO_Y, $data->product_width, $eaotYOffset) ,
            ],
            'highestZ' => $this->highestZ($data->product_height, $data->system_pallet_height, $eaot->z_height  * self::INCH) ,
            'lowestZ' => $this->lowestZ($data->product_height, $data->pallet_height, $eaot->z_height  * self::INCH) ,
        ];

    }

    public function conveyorPalletReachMath($data, $eaot): array
    {
        $eaotYOffset = $eaot->name == 'Dual vacuum' ? $data->product_width / 2 : $eaot->y_offset * self::INCH;
        return [
            'pallet_zero' => [
                'x' => $this::CONVEYOR_PALLET_ZERO_X,
                'y' => $this::CONVEYOR_PALLET_ZERO_Y ,
                'z' => $this::CONVEYOR_PALLET_ZERO_Z ,
            ],
            'pallet_top' => $data->pallet_height,
            'conveyor_top' => self::CONVEYOR_PALLET_TOP ,
            'max_x' => $this->maxX(self::CONVEYOR_PALLET_ZERO_X , $data->product_width),
            'min_x' => $this->minX(self::CONVEYOR_PALLET_ZERO_X, $data->product_width, $data->pallet_length),
            'max_y' => $this->maxY($data->pallet_width, self::CONVEYOR_PALLET_ZERO_Y, $data->product_width, $eaotYOffset),
            'min_y' => $this->minY(self::CONVEYOR_PALLET_ZERO_Y, $data->product_width),
            'furthestCorner' => [
                'x' => $this->furthestCorner($this->maxX(self::CONVEYOR_PALLET_ZERO_X, $data->product_width), $this->minX(self::CONVEYOR_PALLET_ZERO_X, $data->product_width, $data->pallet_length)),
                'y' => $this->maxY($data->pallet_width, self::CONVEYOR_PALLET_ZERO_Y, $data->product_width, $eaotYOffset),
            ],
            'highestZ' => $this->highestZ($data->product_height, $data->system_pallet_height, $eaot->z_height  * self::INCH, self::CONVEYOR_PALLET_TOP) ,
            'lowestZ' => $this->lowestZ($data->product_height, $data->pallet_height, $eaot->z_height  * self::INCH, self::CONVEYOR_PALLET_TOP),
        ];
    }

    public function maxFlorReach($robotRobotBaseHeight, $robotReachCenterHeight, $floorPalletReachMath)
    {
        return max(
            sqrt(
                pow(($robotRobotBaseHeight + $robotReachCenterHeight) - $floorPalletReachMath['highestZ'], 2)
                + pow($floorPalletReachMath['furthestCorner']['x'], 2)
                + pow($floorPalletReachMath['furthestCorner']['y'], 2)
            ),
            sqrt(

                pow(($robotRobotBaseHeight + $robotReachCenterHeight) - $floorPalletReachMath['lowestZ'], 2)
                + pow($floorPalletReachMath['furthestCorner']['x'], 2)
                + pow($floorPalletReachMath['furthestCorner']['y'], 2)
            )
        );
    }

    public function maxConveyorReach($robotRobotBaseHeight, $robotReachCenterHeight, $conveyorPalletReachMath, $floorPalletReachMath)
    {
        return max(
            sqrt(
                pow(($robotRobotBaseHeight + $robotReachCenterHeight) - $conveyorPalletReachMath['highestZ'], 2)
                + pow($conveyorPalletReachMath['furthestCorner']['x'], 2)
                +  pow($conveyorPalletReachMath['furthestCorner']['y'], 2)),
            sqrt(
                pow(($robotRobotBaseHeight + $robotReachCenterHeight) - $conveyorPalletReachMath['lowestZ'], 2)
                +  pow($floorPalletReachMath['furthestCorner']['x'], 2)
                +  pow($floorPalletReachMath['furthestCorner']['y'], 2)
            )
        );
    }

    public function reachRequired($maxFlorReach, $maxConveyorReach, $floorPallets, $conveyorPallets)
    {
        $expr1 = ($floorPallets) ? $maxFlorReach : 0;
        $expr2 = ($conveyorPallets) ? $maxConveyorReach : 0;

        return max($expr1, $expr2) / (self::REACH_SAFETY_FACTOR / 100);
    }
}

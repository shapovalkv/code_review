<?php

namespace App\Services;

use App\Models\MeasurementSystem;

class MeasurementSystemConfigService
{
    const INCH = 25.4;
    const FUNT = 2.2046226;

    const MEASURE_SYSTEM_METRIC = 'metric';
    const MEASURE_SYSTEM_IMPERIAL = 'imperial';
    const MEASURE_SYSTEM_WEIGHT = 'weight';
    const MEASURE_SYSTEM_DIMENSIONS = 'dimensions';


    public function getConfig($measurementSystem)
    {
        $records = MeasurementSystem::all();

        $defaultValues = [];

        foreach ($records as $record) {
            $defaultValues[$record->field] = [
                'value' => match (true) {
                    $record->type == self::MEASURE_SYSTEM_WEIGHT && $measurementSystem == self::MEASURE_SYSTEM_IMPERIAL => $this->getWeight($record->value, $measurementSystem),
                    $record->type == self::MEASURE_SYSTEM_DIMENSIONS && $measurementSystem == self::MEASURE_SYSTEM_IMPERIAL => $this->getDimension($record->value, $measurementSystem),
                    default => $record->value,
                },
                'code' => match (true){
                    $record->type == self::MEASURE_SYSTEM_WEIGHT && $measurementSystem == self::MEASURE_SYSTEM_IMPERIAL => 'lb',
                    $record->type == self::MEASURE_SYSTEM_WEIGHT && $measurementSystem == self::MEASURE_SYSTEM_METRIC => 'kg',
                    $record->type == self::MEASURE_SYSTEM_DIMENSIONS && $measurementSystem == self::MEASURE_SYSTEM_IMPERIAL => 'in',
                    $record->type == self::MEASURE_SYSTEM_DIMENSIONS && $measurementSystem == self::MEASURE_SYSTEM_METRIC => 'mm',
                    default => ''
                }
            ];
        }

        return $defaultValues;
    }

    public function getDimension($value, $measurementSystem)
    {
        if ($measurementSystem == self::MEASURE_SYSTEM_METRIC){
            return $value;
        } else {
            return round($value / self::INCH, 2);
        }
    }

    public function getReverceDimension($value)
    {
        return round($value * self::INCH, 2);
    }

    public function getWeight($value, $measurementSystem)
    {
        if ($measurementSystem == self::MEASURE_SYSTEM_METRIC){
            return $value;
        } else {
            return round($value * self::FUNT, 2);
        }
    }

    public function getReverceWeight($value)
    {
        return round($value / self::FUNT, 2);
    }

    public function recalculateSavingValues($data, $measurementSystem)
    {
        if ($measurementSystem == self::MEASURE_SYSTEM_METRIC) {
            return $data;
        }

        if ($measurementSystem == self::MEASURE_SYSTEM_IMPERIAL){
            foreach ($data as $key => $field){
                $recalculatedValues[$key] = match ($key) {
                    'product_length',
                    'product_width',
                    'product_height',
                    'pallet_width',
                    'pallet_height',
                    'pallet_length',
                    'system_pallet_height' => $this->getReverceDimension($field),
                    'product_weight' => $this->getReverceWeight($field),
                    default => $field
                };
            }
        }
        return $recalculatedValues ?? $data;
    }
}

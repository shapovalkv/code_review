<?php

namespace Modules\User\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Order\Models\OrderItem;
use Modules\User\Models\Plan;

class PlanReportService
{

    public const SEPARATE_WEEK = 'week';
    public const SEPARATE_MONTH = 'month';
    public const SEPARATE_YEAR = 'year';

    public function getGroupedReportBuilder(array $planTypes, array $planIds, ?string $separate = null, ?Carbon $from = null, ?Carbon $to = null, string $orderDirection = 'DESC'): Builder
    {
        $period = match ($separate) {
            self::SEPARATE_WEEK => '%Y-%u',
            self::SEPARATE_MONTH => '%m/%Y',
            self::SEPARATE_YEAR => '%Y',
            default => '%Y',
        };

        $builder = OrderItem::query()
            ->select('*')
            ->selectRaw('sum(subtotal) as total')
            ->selectRaw('count(id) as count')
            ->selectRaw('DATE_FORMAT(created_at, \'' . $period . '\') AS period')
            ->where('object_model', 'plan')
            ->whereHas(OrderItem::RELATION_PLAN, static function (Builder $belongsTo) use ($planTypes) {
                if ($planTypes !== []) {
                    $belongsTo->whereIn('plan_type', $planTypes);
                }
                $belongsTo->with(Plan::RELATION_ROLE);
            });

        if ($from) {
            $builder->where('created_at', '>=', $from);
        }

        if ($to) {
            $builder->where('created_at', '<=', $to);
        }

        if ($planIds !== []) {
            $builder->whereIn('object_id', $planIds);
        }

        return $builder->groupByRaw('DATE_FORMAT(created_at, \'' . $period . '\')')
            ->groupBy('object_id')
            ->orderByRaw('DATE_FORMAT(created_at, \'' . $period . '\') ' . $orderDirection)
            ->orderBy('object_id');
    }

    public function getSalesChartData(Collection $items): array
    {
        $chartSalesData = [
            'datasets' => [],
            'labels'   => [],
        ];

        $dataSet = [
            'label'                => 'Total sales',
            'data'                 => [],
            'backgroundColor'      => '#36A2EB',
            'pointBorderColor'     => '#36A2EB',
            'borderColor'          => '#36A2EB',
            'pointBackgroundColor' => '#36A2EB',
        ];

        foreach ($items as $item) {
            if (isset($dataSet['data'][$item->period])) {
                $dataSet['data'][$item->period] += $item->total;
            } else {
                $dataSet['data'][$item->period] = $item->total;
            }

            $month = Carbon::createFromFormat('m/Y', $item->period)->format('F');
            $chartSalesData['labels'][$item->period] = $month;
        }

        $firstPeriod = Carbon::createFromFormat('m/Y', array_key_first($chartSalesData['labels']))->setDay(1)->setTime(0, 0);
        $diffInMonths = $firstPeriod->diffInMonths(
            Carbon::createFromFormat('m/Y', array_key_last($chartSalesData['labels']))
                ->setDay(1)
                ->setTime(0, 0)
        );

        for ($i = 0; $i <= $diffInMonths; $i++) {
            if (!isset($chartSalesData['labels'][$firstPeriod->addMonth()->format('m/Y')])) {
                $dataSet['data'][$firstPeriod->format('m/Y')] = 0;
                $chartSalesData['labels'][$firstPeriod->format('m/Y')] = $firstPeriod->format('F');
            }
        }

        ksort($dataSet['data']);
        ksort($chartSalesData['labels']);

        $dataSet['data'] = array_values($dataSet['data']);

        $chartSalesData['datasets'][] = $dataSet;
        $chartSalesData['labels'] = array_values($chartSalesData['labels']);

        return $chartSalesData;
    }

    public function getPlanChartData(Collection $items): array
    {
        $chartPlansData = [
            'datasets' => [],
            'labels'   => [],
        ];

        $data = [];
        $plans = [];

        /** @var OrderItem $item */
        foreach ($items as $item) {
            if ($item->plan) {
                $plans[$item->object_id] = $item->plan;
                if (isset($data[$item->object_id][$item->period])) {
                    $data[$item->object_id][$item->period] += $item->total;
                } else {
                    $data[$item->object_id][$item->period] = $item->total;
                }
                $month = Carbon::createFromFormat('m/Y', $item->period)->format('F');
                $chartPlansData['labels'][$item->period] = $month;
            }
        }

        $firstPeriod = Carbon::createFromFormat('m/Y', array_key_first($chartPlansData['labels']))->setDay(1)->setTime(0, 0);
        $diffInMonths = $firstPeriod->diffInMonths(
            Carbon::createFromFormat('m/Y', array_key_last($chartPlansData['labels']))
                ->setDay(1)
                ->setTime(0, 0)
        );

        for ($i = 0; $i <= $diffInMonths; $i++) {
            if (!isset($chartSalesData['labels'][$firstPeriod->addMonth()->format('m/Y')])) {
                $chartPlansData['labels'][$firstPeriod->format('m/Y')] = $firstPeriod->format('F');
            }
        }

        ksort($chartPlansData['labels']);

        foreach ($chartPlansData['labels'] as $period => $month) {
            foreach ($data as $planId => $periodData) {
                if (!isset($data[$planId][$period])) {
                    $data[$planId][$period] = '0';
                    ksort($data[$planId]);
                }
            }
        }

        foreach ($data as $planId => $periodData) {
            $cacheKey = 'plan-report-chart-' . $planId;
            if (Cache::has($cacheKey)) {
                $color = Cache::get($cacheKey);
            } else {
                $color = '#' . $this->randomColor();
                Cache::add($cacheKey, $color);
            }
            $chartPlansData['datasets'][] = [
                'label'                => $plans[$planId]->title . ' (' . $plans[$planId]->role->name . ')',
                'data'                 => array_values($periodData),
                'backgroundColor'      => $color,
                'pointBorderColor'     => $color,
                'borderColor'          => $color,
                'pointBackgroundColor' => $color,
            ];
        }

        $chartPlansData['labels'] = array_values($chartPlansData['labels']);

        return $chartPlansData;
    }

    public function randomColorPart(): string
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    public function randomColor(): string
    {
        return $this->randomColorPart() . $this->randomColorPart() . $this->randomColorPart();
    }

}

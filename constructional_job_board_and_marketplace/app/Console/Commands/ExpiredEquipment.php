<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Equipment\Events\AutomaticEquipmentExpiration;
use Modules\Equipment\Models\Equipment;

class ExpiredEquipment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'equipment:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search and move to draft expired equipment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!empty($equipments = $this->getExpiredEquipment())) {
            foreach ($equipments as $equipment) {
                $equipment->status = 'draft';
                $equipment->is_featured = 0;
                $equipment->save();

                event(new AutomaticEquipmentExpiration($equipment));
            }
        }
    }

    public function getExpiredEquipment()
    {
        return Equipment::query()
            ->where('expiration_date', '<=', Carbon::now())
            ->get();
    }
}

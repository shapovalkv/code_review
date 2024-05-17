<?php

namespace App\Livewire;

use App\Models\PalletizerConfigurationSegment;
use App\Models\PalletizerModule;
use Livewire\Component;
use Livewire\WithPagination;

class AdminPalletModuleList extends Component
{
    use WithPagination;

    public $modules;

    #[Url]
    public $search = '';

    #[Url]
    public $selectedCategory = null;

    public function render()
    {
        return view('livewire.admin-pallet-module-list', [
            'categories' => PalletizerConfigurationSegment::all(),
            'palletizer_modules' => PalletizerModule::query()
                ->when(strlen($this->search) >= 3, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->when($this->selectedCategory, function ($query) {
                    $query->where('segment_id', $this->selectedCategory);
                })
                ->paginate(10),
        ]);
    }
}

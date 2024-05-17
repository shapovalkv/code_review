<?php

namespace App\Livewire;

use App\Models\Lead;
use Livewire\Component;

class AdminLeadsList extends Component
{
    public function render()
    {
        return view('livewire.admin-leads-list', [
            'leads' => Lead::paginate(10)
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\Lead;
use App\Models\LeadProductConfiguration;
use Livewire\Component;

class AdminLead extends Component
{
    public $lead;

    public function mount($lead){
        $this->lead = Lead::find($lead);
    }

    public function render()
    {
        return view('livewire.admin-lead', [
            'configurations' => LeadProductConfiguration::query()->where('lead_id', $this->lead->id)->get()
        ]);
    }
}

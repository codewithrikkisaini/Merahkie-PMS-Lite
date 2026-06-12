<?php
namespace App\Livewire\Maintenance;
use Livewire\Component;
class MaintenanceList extends Component {
    public function render() { return view('livewire.placeholder', ['title' => 'Maintenance Tickets'])->layout('layouts.app'); }
}

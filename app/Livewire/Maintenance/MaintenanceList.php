<?php
namespace App\Livewire\Maintenance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaintenanceTicket;

class MaintenanceList extends Component {
    use WithPagination;
    
    public $search = '';
    public $isModalOpen = false;
    public $room_id, $issue, $priority = 'Medium', $status = 'Open';

    public function openModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->room_id = '';
        $this->issue = '';
        $this->priority = 'Medium';
        $this->status = 'Open';
    }

    public function store()
    {
        $this->validate([
            'room_id' => 'required|exists:rooms,id',
            'issue' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High,Critical',
        ]);

        MaintenanceTicket::create([
            'room_id' => $this->room_id,
            'issue' => $this->issue,
            'priority' => $this->priority,
            'status' => $this->status,
            'reported_by' => auth()->id(),
        ]);

        $this->dispatch('toast', message: 'Ticket created successfully.', type: 'success');
        $this->closeModal();
    }

    public function updateStatus($id, $status)
    {
        $ticket = MaintenanceTicket::find($id);
        if ($ticket) {
            $ticket->update(['status' => $status]);
            
            // Optionally update room status
            if ($status == 'Resolved') {
                $ticket->room->update(['status' => 'Available']);
            } elseif ($status == 'In Progress' || $status == 'Open') {
                $ticket->room->update(['status' => 'Maintenance']);
            }
            
            $this->dispatch('toast', message: 'Ticket status updated.', type: 'success');
        }
    }

    public function render() { 
        $query = MaintenanceTicket::with(['room', 'reporter', 'assignee']);
        
        if ($this->search) {
            $query->whereHas('room', function($q) {
                $q->where('room_number', 'like', '%' . $this->search . '%');
            })->orWhere('issue', 'like', '%' . $this->search . '%');
        }

        $tickets = $query->latest()->paginate(10);
        $rooms = \App\Models\Room::all();
        
        return view('livewire.maintenance.maintenance-list', compact('tickets', 'rooms'))->layout('layouts.app', ['title' => 'Maintenance Tickets']); 
    }
}

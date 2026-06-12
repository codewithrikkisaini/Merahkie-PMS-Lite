<?php
namespace App\Livewire\Housekeeping;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Housekeeping;

class HousekeepingList extends Component {
    use WithPagination;
    
    public $search = '';
    public $isModalOpen = false;
    public $room_id, $status = 'Dirty', $notes;

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
        $this->status = 'Dirty';
        $this->notes = '';
    }

    public function store()
    {
        $this->validate([
            'room_id' => 'required|exists:rooms,id',
            'status' => 'required|in:Clean,Dirty,In Progress',
            'notes' => 'nullable|string',
        ]);

        Housekeeping::create([
            'room_id' => $this->room_id,
            'status' => $this->status,
            'notes' => $this->notes,
            'updated_by' => auth()->id(),
        ]);

        if ($this->status == 'Clean') {
            \App\Models\Room::find($this->room_id)->update(['status' => 'Available']);
        }

        $this->dispatch('toast', message: 'Task created successfully.', type: 'success');
        $this->closeModal();
    }

    public function updateStatus($id, $status)
    {
        $housekeeping = Housekeeping::find($id);
        if ($housekeeping) {
            $housekeeping->update([
                'status' => $status,
                'updated_by' => auth()->id(),
            ]);
            
            // Optionally update room status if housekeeping is completed
            if ($status == 'Clean') {
                $housekeeping->room->update(['status' => 'Available']);
            }
            
            $this->dispatch('toast', message: 'Housekeeping status updated.', type: 'success');
        }
    }

    public function render() { 
        $query = Housekeeping::with(['room', 'updater']);
        
        if ($this->search) {
            $query->whereHas('room', function($q) {
                $q->where('room_number', 'like', '%' . $this->search . '%');
            });
        }

        $records = $query->latest()->paginate(10);
        $rooms = \App\Models\Room::all();
        
        return view('livewire.housekeeping.housekeeping-list', compact('records', 'rooms'))->layout('layouts.app', ['title' => 'Housekeeping']); 
    }
}

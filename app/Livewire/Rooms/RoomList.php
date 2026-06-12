<?php

namespace App\Livewire\Rooms;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Room;

class RoomList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Room::query();

        if ($this->search) {
            $query->where('room_number', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->typeFilter) {
            $query->whereHas('roomType', function($q) {
                $q->where('name', $this->typeFilter);
            });
        }

        $rooms = $query->paginate(10);

        return view('livewire.rooms.room-list', compact('rooms'))
            ->layout('layouts.app', ['title' => 'Rooms']);
    }
}

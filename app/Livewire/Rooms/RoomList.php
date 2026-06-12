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

    public $isModalOpen = false;
    public $room_number, $room_type_id, $price, $status = 'Available';

    public function updatingSearch()
    {
        $this->resetPage();
    }

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
        $this->room_number = '';
        $this->room_type_id = '';
        $this->price = '';
        $this->status = 'Available';
    }

    public function store()
    {
        $this->validate([
            'room_number' => 'required|unique:rooms,room_number',
            'room_type_id' => 'required|exists:room_types,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Occupied,Maintenance',
        ]);

        Room::create([
            'room_number' => $this->room_number,
            'room_type_id' => $this->room_type_id,
            'price' => $this->price,
            'status' => $this->status,
        ]);

        $this->dispatch('toast', message: 'Room created successfully.', type: 'success');
        $this->closeModal();
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
        $roomTypes = \App\Models\RoomType::all();

        return view('livewire.rooms.room-list', compact('rooms', 'roomTypes'))
            ->layout('layouts.app', ['title' => 'Rooms']);
    }
}

<?php

namespace App\Livewire\Reservations;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Reservation;

class ReservationList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public $isModalOpen = false;
    public $guest_id, $room_id, $check_in_date, $check_out_date, $status = 'Pending';

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
        $this->guest_id = '';
        $this->room_id = '';
        $this->check_in_date = '';
        $this->check_out_date = '';
        $this->status = 'Pending';
    }

    public function store()
    {
        $this->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'status' => 'required|in:Pending,Confirmed,Checked-In,Checked-Out,Cancelled',
        ]);

        Reservation::create([
            'guest_id' => $this->guest_id,
            'room_id' => $this->room_id,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
            'status' => $this->status,
        ]);

        $this->dispatch('toast', message: 'Reservation created successfully.', type: 'success');
        $this->closeModal();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Reservation::with(['guest', 'room']);

        if ($this->search) {
            $query->whereHas('guest', function($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%');
            })->orWhereHas('room', function($q) {
                $q->where('room_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $reservations = $query->latest()->paginate(10);
        $guests = \App\Models\Guest::all();
        $rooms = \App\Models\Room::all();

        return view('livewire.reservations.reservation-list', compact('reservations', 'guests', 'rooms'))
            ->layout('layouts.app', ['title' => 'Reservations']);
    }
}

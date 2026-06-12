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

        return view('livewire.reservations.reservation-list', compact('reservations'))
            ->layout('layouts.app', ['title' => 'Reservations']);
    }
}

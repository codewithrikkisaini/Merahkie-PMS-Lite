<?php

namespace App\Livewire\Guests;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Guest;

class GuestList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Guest::query();

        if ($this->search) {
            $query->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
        }

        $guests = $query->latest()->paginate(10);

        return view('livewire.guests.guest-list', compact('guests'))
            ->layout('layouts.app', ['title' => 'Guests']);
    }
}

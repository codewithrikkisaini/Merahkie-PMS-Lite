<?php

namespace App\Livewire\Guests;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Guest;

class GuestList extends Component
{
    use WithPagination;

    public $search = '';
    
    public $isModalOpen = false;
    public $name, $email, $phone, $nationality, $passport_number, $address;

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
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->nationality = '';
        $this->passport_number = '';
        $this->address = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
        ]);

        Guest::create([
            'guest_id' => 'GST-' . strtoupper(substr(uniqid(), -6)),
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'nationality' => $this->nationality,
            'passport_number' => $this->passport_number,
            'address' => $this->address,
        ]);

        $this->dispatch('toast', message: 'Guest created successfully.', type: 'success');
        $this->closeModal();
    }

    public function render()
    {
        $query = Guest::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
        }

        $guests = $query->latest()->paginate(10);

        return view('livewire.guests.guest-list', compact('guests'))
            ->layout('layouts.app', ['title' => 'Guests']);
    }
}

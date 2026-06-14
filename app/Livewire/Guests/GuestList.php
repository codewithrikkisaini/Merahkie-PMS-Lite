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
    public $edit_id = null;
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
        $this->edit_id = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->nationality = '';
        $this->passport_number = '';
        $this->address = '';
    }

    public function edit($id)
    {
        $guest = Guest::findOrFail($id);
        $this->edit_id = $id;
        $this->name = $guest->name;
        $this->email = $guest->email;
        $this->phone = $guest->phone;
        $this->nationality = $guest->nationality;
        $this->passport_number = $guest->passport_number;
        $this->address = $guest->address;

        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Guest::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Guest deleted successfully.', type: 'success');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
        ]);

        if ($this->edit_id) {
            $guest = Guest::findOrFail($this->edit_id);
            $guest->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'nationality' => $this->nationality,
                'passport_number' => $this->passport_number,
                'address' => $this->address,
            ]);
            $this->dispatch('toast', message: 'Guest updated successfully.', type: 'success');
        } else {
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
        }

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

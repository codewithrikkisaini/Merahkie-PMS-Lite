<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Reservation;
use App\Models\CheckIn as CheckInModel;
use App\Models\Room;
use Illuminate\Support\Str;

class CheckIn extends Component {
    public $search = '';

    public function processCheckIn($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        
        if ($reservation->status !== 'Confirmed') {
            $this->dispatch('toast', message: 'Only confirmed reservations can be checked in.', type: 'error');
            return;
        }

        // Create CheckIn record
        CheckInModel::create([
            'reservation_id' => $reservation->id,
            'booking_code' => 'BKG-' . strtoupper(Str::random(6)),
            'checkin_datetime' => now(),
            'user_id' => auth()->id(),
            'remarks' => 'Checked in via operations',
        ]);

        // Update reservation status
        $reservation->update(['status' => 'Checked In']);

        // Update room status
        if ($reservation->room) {
            $reservation->room->update(['status' => 'Occupied']);
        }

        $this->dispatch('toast', message: 'Guest checked in successfully.', type: 'success');
    }

    public function render() { 
        $query = Reservation::with(['guest', 'room'])->whereIn('status', ['Confirmed', 'Pending']);
        
        if ($this->search) {
            $query->whereHas('guest', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        $reservations = $query->latest()->paginate(10);

        return view('livewire.check-in', compact('reservations'))->layout('layouts.app', ['title' => 'Check In']); 
    }
}

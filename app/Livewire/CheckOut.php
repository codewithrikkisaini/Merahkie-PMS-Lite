<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Reservation;
use App\Models\CheckOut as CheckOutModel;
use App\Models\Room;
use Carbon\Carbon;

class CheckOut extends Component {
    public $search = '';

    public function processCheckOut($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        
        if ($reservation->status !== 'Checked In') {
            $this->dispatch('toast', message: 'Only checked-in reservations can be checked out.', type: 'error');
            return;
        }

        $checkInDate = Carbon::parse($reservation->check_in_date);
        $checkOutDate = now();
        $nights = $checkInDate->diffInDays($checkOutDate) == 0 ? 1 : $checkInDate->diffInDays($checkOutDate);
        
        $pricePerNight = $reservation->room ? $reservation->room->price : 0;
        $subtotal = $nights * $pricePerNight;
        $tax = $subtotal * 0.10; // 10% tax for example
        $totalAmount = $subtotal + $tax;

        // Create CheckOut record
        CheckOutModel::create([
            'reservation_id' => $reservation->id,
            'checkout_datetime' => $checkOutDate,
            'nights' => $nights,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total_amount' => $totalAmount,
        ]);

        // Update reservation status
        $reservation->update(['status' => 'Checked Out']);

        // Update room status
        if ($reservation->room) {
            $reservation->room->update(['status' => 'Available']); // Or Housekeeping
        }

        $this->dispatch('toast', message: 'Guest checked out successfully.', type: 'success');
    }

    public function render() { 
        $query = Reservation::with(['guest', 'room'])->where('status', 'Checked In');
        
        if ($this->search) {
            $query->whereHas('guest', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        $reservations = $query->latest()->paginate(10);

        return view('livewire.check-out', compact('reservations'))->layout('layouts.app', ['title' => 'Check Out']); 
    }
}

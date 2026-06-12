<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\CheckIn;
use App\Models\CheckOut;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'Available')->count();
        $occupiedRooms = Room::where('status', 'Occupied')->count();
        $todaysCheckIns = Reservation::whereDate('check_in_date', Carbon::today())->count();
        $todaysCheckOuts = Reservation::whereDate('check_out_date', Carbon::today())->count();
        $recentReservations = Reservation::with(['guest', 'room'])->latest()->take(5)->get();

        return view('livewire.dashboard', [
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'occupiedRooms' => $occupiedRooms,
            'todaysCheckIns' => $todaysCheckIns,
            'todaysCheckOuts' => $todaysCheckOuts,
            'recentReservations' => $recentReservations
        ])->layout('layouts.app', ['title' => 'Dashboard', 'hotelName' => 'Merahkie PMS Lite']);
    }
}

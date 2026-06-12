<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Reservation;

class Calendar extends Component
{
    public function render()
    {
        $reservations = Reservation::with(['guest', 'room'])->get();

        $events = [];

        foreach ($reservations as $res) {
            $title = ($res->guest->full_name ?? 'Guest') . ' - Room ' . ($res->room->room_number ?? 'N/A');
            
            $color = '#3b82f6'; // blue
            if ($res->status == 'Confirmed') $color = '#22c55e'; // green
            if ($res->status == 'Pending') $color = '#eab308'; // yellow
            if ($res->status == 'Checked-In') $color = '#8b5cf6'; // purple
            if ($res->status == 'Cancelled') $color = '#ef4444'; // red

            $events[] = [
                'id' => $res->id,
                'title' => $title,
                'start' => $res->check_in_date,
                'end' => \Carbon\Carbon::parse($res->check_out_date)->addDay()->format('Y-m-d'), // FullCalendar exclusive end date
                'color' => $color,
            ];
        }

        return view('livewire.calendar', ['events' => $events])
            ->layout('layouts.app', ['title' => 'Booking Calendar']);
    }
}

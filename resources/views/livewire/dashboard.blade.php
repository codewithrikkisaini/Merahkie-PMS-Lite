<div>
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Dashboard</h1>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
        <!-- Total Rooms -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium">Total Rooms</h3>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                    <i class="fas fa-door-closed text-blue-500"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalRooms }}</div>
        </div>

        <!-- Available Rooms -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium">Available Rooms</h3>
                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                    <i class="fas fa-door-open text-green-500"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $availableRooms }}</div>
        </div>

        <!-- Occupied Rooms -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium">Occupied Rooms</h3>
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                    <i class="fas fa-bed text-red-500"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $occupiedRooms }}</div>
        </div>

        <!-- Today's Check-Ins -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium">Today's Check-Ins</h3>
                <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt text-purple-500"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $todaysCheckIns }}</div>
        </div>

        <!-- Today's Check-Outs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium">Today's Check-Outs</h3>
                <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center">
                    <i class="fas fa-sign-out-alt text-orange-500"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $todaysCheckOuts }}</div>
        </div>
    </div>

    <!-- Recent Reservations -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-lg font-bold text-gray-900">Recent Reservations</h2>
            <a href="{{ route('reservations.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Guest Name</th>
                        <th class="px-6 py-3 font-medium">Room</th>
                        <th class="px-6 py-3 font-medium">Check In</th>
                        <th class="px-6 py-3 font-medium">Check Out</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentReservations as $reservation)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $reservation->guest->full_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $reservation->room->room_number ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            @if($reservation->status == 'Confirmed')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Confirmed</span>
                            @elseif($reservation->status == 'Pending')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                            @elseif($reservation->status == 'Checked-In')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Checked-In</span>
                            @elseif($reservation->status == 'Checked-Out')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Checked-Out</span>
                            @elseif($reservation->status == 'Cancelled')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $reservation->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No recent reservations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div>
    <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-600 mb-8 tracking-tight">Dashboard Overview</h1>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
        <!-- Total Rooms -->
        <div class="pms-card p-6 flex flex-col justify-between group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-slate-500 text-sm font-semibold tracking-wide uppercase">Total Rooms</h3>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30 transform group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-door-closed text-white text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-extrabold text-slate-900">{{ $totalRooms }}</div>
        </div>

        <!-- Available Rooms -->
        <div class="pms-card p-6 flex flex-col justify-between group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-slate-500 text-sm font-semibold tracking-wide uppercase">Available Rooms</h3>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30 transform group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-door-open text-white text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-extrabold text-slate-900">{{ $availableRooms }}</div>
        </div>

        <!-- Occupied Rooms -->
        <div class="pms-card p-6 flex flex-col justify-between group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-slate-500 text-sm font-semibold tracking-wide uppercase">Occupied Rooms</h3>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/30 transform group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-bed text-white text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-extrabold text-slate-900">{{ $occupiedRooms }}</div>
        </div>

        <!-- Today's Check-Ins -->
        <div class="pms-card p-6 flex flex-col justify-between group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-slate-500 text-sm font-semibold tracking-wide uppercase">Today's Check-Ins</h3>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 transform group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-sign-in-alt text-white text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-extrabold text-slate-900">{{ $todaysCheckIns }}</div>
        </div>

        <!-- Today's Check-Outs -->
        <div class="pms-card p-6 flex flex-col justify-between group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-slate-500 text-sm font-semibold tracking-wide uppercase">Today's Check-Outs</h3>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/30 transform group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-sign-out-alt text-white text-lg"></i>
                </div>
            </div>
            <div class="text-4xl font-extrabold text-slate-900">{{ $todaysCheckOuts }}</div>
        </div>
    </div>

    <!-- Recent Reservations -->
    <div class="pms-card mb-4">
        <div class="pms-card-header bg-white/30">
            <h2 class="text-lg font-bold text-slate-800">Recent Reservations</h2>
            <a href="{{ route('reservations.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">View All &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="pms-table">
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

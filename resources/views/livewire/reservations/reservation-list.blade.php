<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Reservations</h1>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
            <i class="fas fa-calendar-plus mr-2"></i> New Reservation
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-4 bg-gray-50/50">
            <div class="w-full md:w-96">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by guest name or room..." class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Checked-In">Checked-In</option>
                    <option value="Checked-Out">Checked-Out</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Guest</th>
                        <th class="px-6 py-3 font-medium">Room</th>
                        <th class="px-6 py-3 font-medium">Dates</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reservations as $res)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $res->guest->full_name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $res->guest->phone ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">Room {{ $res->room->room_number ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $res->room->roomType->name ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="text-gray-900"><span class="text-gray-500">In:</span> {{ \Carbon\Carbon::parse($res->check_in_date)->format('M d, Y') }}</div>
                            <div class="text-gray-900"><span class="text-gray-500">Out:</span> {{ \Carbon\Carbon::parse($res->check_out_date)->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($res->status == 'Confirmed')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Confirmed</span>
                            @elseif($res->status == 'Pending')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                            @elseif($res->status == 'Checked-In')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Checked-In</span>
                            @elseif($res->status == 'Checked-Out')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Checked-Out</span>
                            @elseif($res->status == 'Cancelled')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $res->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <button class="text-indigo-600 hover:text-indigo-900 p-2"><i class="fas fa-edit"></i></button>
                            @if(auth()->check() && auth()->user()->role && auth()->user()->role->name === 'Admin')
                            <button class="text-red-600 hover:text-red-900 p-2"><i class="fas fa-trash-alt"></i></button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-calendar-times text-gray-300 text-4xl mb-3"></i>
                                <p>No reservations found matching your criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reservations->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $reservations->links() }}
        </div>
        @endif
    </div>
</div>

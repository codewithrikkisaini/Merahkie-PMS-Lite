<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Check-Out Operations</h1>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('message') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-4 bg-gray-50/50">
            <div class="w-full md:w-96">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by guest name or phone..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-9">
                </div>
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
                    @forelse($reservations as $reservation)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $reservation->guest->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $reservation->guest->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $reservation->room->room_number ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $reservation->room->roomType->name ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">In: {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-900 mt-1">Out: {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $reservation->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="processCheckOut({{ $reservation->id }})" onclick="confirm('Are you sure you want to check out this guest?') || event.stopImmediatePropagation()" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-sign-out-alt mr-1"></i> Check Out
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-door-open text-gray-300 text-4xl mb-3"></i>
                                <p>No guests currently checked in.</p>
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

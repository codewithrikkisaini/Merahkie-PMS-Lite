<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Rooms</h1>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add Room
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-4 bg-gray-50/50">
            <div class="w-full md:w-64">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search room number..." class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="Available">Available</option>
                    <option value="Occupied">Occupied</option>
                    <option value="Maintenance">Maintenance</option>
                </select>
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="typeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Types</option>
                    <option value="King Room">King Room</option>
                    <option value="Twin Room">Twin Room</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Room #</th>
                        <th class="px-6 py-3 font-medium">Type</th>
                        <th class="px-6 py-3 font-medium">Price</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rooms as $room)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $room->room_number }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $room->roomType->name ?? 'Standard' }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">${{ number_format($room->price, 2) }}</td>
                        <td class="px-6 py-4">
                            @if($room->status == 'Available')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i> Available</span>
                            @elseif($room->status == 'Occupied')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i> Occupied</span>
                            @elseif($room->status == 'Maintenance')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-tools mr-1"></i> Maintenance</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $room->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-indigo-600 hover:text-indigo-900 p-2"><i class="fas fa-edit"></i></button>
                            @if(auth()->user()->role->name === 'Admin')
                            <button class="text-red-600 hover:text-red-900 p-2"><i class="fas fa-trash-alt"></i></button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-search text-gray-300 text-4xl mb-3"></i>
                                <p>No rooms found matching your criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rooms->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $rooms->links() }}
        </div>
        @endif
    </div>
</div>

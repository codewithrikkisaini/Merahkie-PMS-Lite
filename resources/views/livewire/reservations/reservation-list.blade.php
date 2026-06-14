<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Reservations</h1>
        <button wire:click="openModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
            <i class="fas fa-calendar-plus mr-2"></i> New Reservation
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-4 bg-gray-50/50">
            <div class="w-full md:w-96">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by guest name or room..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-9">
                </div>
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
                            @if($res->room && in_array($res->room->status, ['Housekeeping', 'Maintenance', 'Dirty', 'Cleaning']))
                                <div class="mt-1">
                                    <span class="px-2 py-0.5 inline-flex text-[10px] leading-4 font-semibold rounded-sm bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-1 mt-0.5 text-[10px]"></i> {{ $res->room->status }} Alert
                                    </span>
                                </div>
                            @endif
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
                            <button wire:click="edit({{ $res->id }})" class="text-indigo-600 hover:text-indigo-900 p-2"><i class="fas fa-edit"></i></button>
                            @if(auth()->check() && auth()->user()->role && auth()->user()->role->name === 'Admin')
                            <button wire:click="delete({{ $res->id }})" wire:confirm="Are you sure you want to delete this reservation?" class="text-red-600 hover:text-red-900 p-2"><i class="fas fa-trash-alt"></i></button>
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

    <!-- Add Reservation Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="store">
                    <div class="bg-blue-500 px-4 py-3 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-white" id="modal-title">
                            {{ $edit_id ? 'Edit Reservation' : 'Add Reservation' }}
                        </h3>
                        <button type="button" wire:click="closeModal" class="text-white hover:text-gray-200 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                        
                    <div class="bg-white px-6 py-6 space-y-4">
                        <div>
                            <label for="guest_id" class="block text-sm font-bold text-gray-700">Guest <span class="text-red-500">*</span></label>
                            <select wire:model="guest_id" id="guest_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1">
                                <option value="">Select Guest</option>
                                @foreach($guests as $guest)
                                    <option value="{{ $guest->id }}">{{ $guest->name }} ({{ $guest->phone ?? $guest->email }})</option>
                                @endforeach
                            </select>
                            @error('guest_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="room_id" class="block text-sm font-bold text-gray-700">Room <span class="text-red-500">*</span></label>
                            <select wire:model="room_id" id="room_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1">
                                <option value="">Select Room</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">Room {{ $room->room_number }} ({{ $room->roomType->name ?? 'Standard' }})</option>
                                @endforeach
                            </select>
                            @error('room_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="check_in_date" class="block text-sm font-bold text-gray-700">Check-in Date <span class="text-red-500">*</span></label>
                                <input type="date" wire:model="check_in_date" id="check_in_date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1">
                                @error('check_in_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="check_out_date" class="block text-sm font-bold text-gray-700">Check-out Date <span class="text-red-500">*</span></label>
                                <input type="date" wire:model="check_out_date" id="check_out_date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1">
                                @error('check_out_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-bold text-gray-700">Status <span class="text-red-500">*</span></label>
                            <select wire:model="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1">
                                <option value="Pending">Pending</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Checked-In">Checked-In</option>
                                <option value="Checked-Out">Checked-Out</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="bg-white border-t border-gray-100 px-4 py-3 flex justify-end space-x-2">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600 focus:outline-none transition-colors">
                            Close
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 focus:outline-none transition-colors">
                            Save Reservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

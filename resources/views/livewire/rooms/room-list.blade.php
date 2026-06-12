<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Rooms</h1>
        <button wire:click="openModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
            <i class="fas fa-plus mr-2"></i> Add Room
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-4 bg-gray-50/50">
            <div class="w-full md:w-64">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search room number..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-9">
                </div>
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Statuses</option>
                    <option value="Available">Available</option>
                    <option value="Occupied">Occupied</option>
                    <option value="Maintenance">Maintenance</option>
                </select>
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="typeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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

    <!-- Add Room Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form wire:submit.prevent="store">
                    <div class="bg-white px-6 py-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Add New Room
                            </h3>
                            <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="room_number" class="block text-sm font-medium text-gray-700">Room Number <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="room_number" id="room_number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mt-1" placeholder="e.g. 101">
                                @error('room_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="room_type_id" class="block text-sm font-medium text-gray-700">Room Type <span class="text-red-500">*</span></label>
                                <select wire:model="room_type_id" id="room_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mt-1">
                                    <option value="">Select Room Type</option>
                                    @foreach($roomTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('room_type_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Price (per night) <span class="text-red-500">*</span></label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm font-medium">$</span>
                                    </div>
                                    <input type="number" step="0.01" wire:model="price" id="price" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-8" placeholder="0.00">
                                </div>
                                @error('price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                <select wire:model="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mt-1">
                                    <option value="Available">Available</option>
                                    <option value="Occupied">Occupied</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                                @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

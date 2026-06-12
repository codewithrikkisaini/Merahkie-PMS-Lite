<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Housekeeping</h1>
        <button wire:click="openModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
            <i class="fas fa-plus mr-2"></i> New Task
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-4 bg-gray-50/50">
            <div class="w-full md:w-96">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by room number..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-9">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Room #</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Last Updated By</th>
                        <th class="px-6 py-3 font-medium">Notes</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($records as $record)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $record->room->room_number ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @if($record->status == 'Clean')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $record->status }}</span>
                            @elseif($record->status == 'Dirty')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ $record->status }}</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $record->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $record->updater->name ?? 'System' }}<br><span class="text-xs text-gray-400">{{ $record->updated_at->diffForHumans() }}</span></td>
                        <td class="px-6 py-4 text-gray-600 text-sm max-w-xs truncate" title="{{ $record->notes }}">{{ $record->notes ?? 'No notes' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <button wire:click="updateStatus({{ $record->id }}, 'Clean')" class="text-xs bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded transition-colors" title="Mark Clean"><i class="fas fa-broom"></i></button>
                                <button wire:click="updateStatus({{ $record->id }}, 'Dirty')" class="text-xs bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded transition-colors" title="Mark Dirty"><i class="fas fa-times"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-broom text-gray-300 text-4xl mb-3"></i>
                                <p>No housekeeping records found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $records->links() }}
        </div>
        @endif
    </div>

    <!-- Add Housekeeping Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="store">
                    <div class="bg-white px-6 py-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Add Housekeeping Task</h3>
                            <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-500"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label for="room_id" class="block text-sm font-medium text-gray-700">Room <span class="text-red-500">*</span></label>
                                <select wire:model="room_id" id="room_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mt-1">
                                    <option value="">Select Room</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}">Room {{ $room->room_number }} ({{ $room->roomType->name ?? 'Standard' }})</option>
                                    @endforeach
                                </select>
                                @error('room_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                <select wire:model="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mt-1">
                                    <option value="Dirty">Dirty</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Clean">Clean</option>
                                </select>
                                @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea wire:model="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mt-1"></textarea>
                                @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                        <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

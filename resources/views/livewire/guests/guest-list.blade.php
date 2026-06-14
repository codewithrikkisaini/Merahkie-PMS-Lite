<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Guests</h1>
        <button wire:click="openModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
            <i class="fas fa-user-plus mr-2"></i> Add Guest
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-4 bg-gray-50/50">
            <div class="w-full md:w-96">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, email, or phone..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-9">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Guest Name</th>
                        <th class="px-6 py-3 font-medium">Contact Info</th>
                        <th class="px-6 py-3 font-medium">Address</th>
                        <th class="px-6 py-3 font-medium">ID Proof</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($guests as $guest)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $guest->name }}</div>
                            <div class="text-xs text-gray-500 mt-1">Added: {{ $guest->created_at->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900"><i class="fas fa-envelope text-gray-400 mr-2 w-4"></i>{{ $guest->email ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-900 mt-1"><i class="fas fa-phone text-gray-400 mr-2 w-4"></i>{{ $guest->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm max-w-xs truncate" title="{{ $guest->address }}">
                            {{ $guest->address ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            <div><span class="font-medium">Nationality:</span> {{ $guest->nationality ?? 'N/A' }}</div>
                            <div><span class="font-medium">Passport:</span> {{ $guest->passport_number ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <button class="text-blue-600 hover:text-blue-900 p-2"><i class="fas fa-eye"></i></button>
                            <button wire:click="edit({{ $guest->id }})" class="text-indigo-600 hover:text-indigo-900 p-2"><i class="fas fa-edit"></i></button>
                            @if(auth()->check() && auth()->user()->role && auth()->user()->role->name === 'Admin')
                            <button wire:click="delete({{ $guest->id }})" wire:confirm="Are you sure you want to delete this guest?" class="text-red-600 hover:text-red-900 p-2"><i class="fas fa-trash-alt"></i></button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                                <p>No guests found matching your criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($guests->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $guests->links() }}
        </div>
        @endif
    </div>

    <!-- Add Guest Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg overflow-hidden">
                <form wire:submit.prevent="store">
                    <div class="bg-blue-500 px-4 py-3 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-white" id="modal-title">
                            {{ $edit_id ? 'Edit Guest' : 'Add Guest' }}
                        </h3>
                        <button type="button" wire:click="closeModal" class="text-white hover:text-gray-200 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                        
                    <div class="bg-white px-6 py-6 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1">
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700">Email</label>
                            <input type="email" wire:model="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1">
                            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-bold text-gray-700">Phone</label>
                            <input type="text" wire:model="phone" id="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1">
                            @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="nationality" class="block text-sm font-bold text-gray-700">Nationality</label>
                            <input type="text" wire:model="nationality" id="nationality" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1">
                        </div>
                        
                        <div>
                            <label for="passport_number" class="block text-sm font-bold text-gray-700">Passport Number</label>
                            <input type="text" wire:model="passport_number" id="passport_number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-bold text-gray-700">Address</label>
                            <textarea wire:model="address" id="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1"></textarea>
                        </div>
                    </div>
                    
                    <div class="bg-white border-t border-gray-100 px-4 py-3 flex justify-end space-x-2">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600 focus:outline-none transition-colors">
                            Close
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 focus:outline-none transition-colors">
                            Save Guest
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

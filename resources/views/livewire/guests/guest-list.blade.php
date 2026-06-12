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
                                Add New Guest
                            </h3>
                            <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="name" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mt-1" placeholder="John Doe">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" wire:model="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mt-1" placeholder="john@example.com">
                                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="text" wire:model="phone" id="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mt-1" placeholder="+1 234 567 890">
                                    @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality</label>
                                    <input type="text" wire:model="nationality" id="nationality" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mt-1" placeholder="e.g. American">
                                </div>
                                <div>
                                    <label for="passport_number" class="block text-sm font-medium text-gray-700">Passport Number</label>
                                    <input type="text" wire:model="passport_number" id="passport_number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mt-1" placeholder="AB1234567">
                                </div>
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea wire:model="address" id="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm mt-1" placeholder="Full address..."></textarea>
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

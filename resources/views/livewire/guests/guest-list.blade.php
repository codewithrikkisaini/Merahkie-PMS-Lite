<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Guests</h1>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
            <i class="fas fa-user-plus mr-2"></i> Add Guest
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-4 bg-gray-50/50">
            <div class="w-full md:w-96">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, email, or phone..." class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
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
                            <div class="font-bold text-gray-900">{{ $guest->full_name }}</div>
                            <div class="text-xs text-gray-500 mt-1">Added: {{ $guest->created_at->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900"><i class="fas fa-envelope text-gray-400 mr-2 w-4"></i>{{ $guest->email ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-900 mt-1"><i class="fas fa-phone text-gray-400 mr-2 w-4"></i>{{ $guest->phone ?? $guest->mobile_number ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm max-w-xs truncate" title="{{ $guest->address }}">
                            {{ $guest->address ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            <div><span class="font-medium">Type:</span> {{ $guest->id_proof_type ?? 'N/A' }}</div>
                            <div><span class="font-medium">No:</span> {{ $guest->id_number ?? 'N/A' }}</div>
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
</div>

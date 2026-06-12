<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Invoices</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-4 bg-gray-50/50">
            <div class="w-full md:w-96">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by invoice number or guest name..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-9">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-indigo-600 text-white text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">Invoice #</th>
                        <th class="px-6 py-3 font-medium">Guest</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                        <th class="px-6 py-3 font-medium">Amount</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $invoice->invoice_number }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $invoice->checkout->reservation->guest->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $invoice->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900">${{ number_format($invoice->checkout->total_amount ?? 0, 2) }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('invoice.view', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900 p-2"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('invoice.download', $invoice->id) }}" class="text-blue-600 hover:text-blue-900 p-2"><i class="fas fa-download"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-file-invoice text-gray-300 text-4xl mb-3"></i>
                                <p>No invoices found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>

<?php
namespace App\Livewire\Invoices;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;

class InvoiceList extends Component {
    use WithPagination;
    
    public $search = '';

    public function render() { 
        $query = Invoice::with(['checkout.reservation.guest']);
        
        if ($this->search) {
            $query->where('invoice_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('checkout.reservation.guest', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
        }
        
        $invoices = $query->latest()->paginate(10);
        
        return view('livewire.invoices.invoice-list', compact('invoices'))->layout('layouts.app', ['title' => 'Invoices']); 
    }
}

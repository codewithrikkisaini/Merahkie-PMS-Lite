<?php
namespace App\Livewire\Users;
use Livewire\Component;
class UserList extends Component {
    public function render() { return view('livewire.placeholder', ['title' => 'Users Management'])->layout('layouts.app'); }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class UsersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = ['search' => ['except' => '']];

    #[\Livewire\Attributes\On('refreshUsers')]
    #[\Livewire\Attributes\On('userSaved')]
    public function refreshUsers()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleActive($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->is_active = !$user->is_active;
            $user->save();
        }

        $this->dispatch('refreshUsers');
    }

    public function toggleApproval($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->is_approved = !$user->is_approved;
            $user->save();
        }

        $this->dispatch('refreshUsers');
    }

    public function render()
    {
        $users = User::when($this->search, function ($q) {
            $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere('username', 'like', "%{$this->search}%");
        })->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.users-table', compact('users'));
    }
}

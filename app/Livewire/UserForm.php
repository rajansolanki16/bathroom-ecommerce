<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserForm extends Component
{
    public $userId;
    public $name;
    public $email;
    public $username;
    public $is_active = true;
    public $is_approved = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'username' => 'required|string|max:100',
    ];

    #[\Livewire\Attributes\On('openUserForm')]
    public function openUserForm($id = null)
    {
        $this->resetValidation();

        if ($id) {
            $this->userId = $id;
            $user = User::find($id);
            if ($user) {
                $this->name = $user->name;
                $this->email = $user->email;
                $this->username = $user->username;
                $this->is_active = $user->is_active;
                $this->is_approved = $user->is_approved;
            }
        } else {
            $this->resetForm();
        }

        $this->dispatch('open-user-modal');
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->username = '';
        $this->is_active = true;
        $this->is_approved = false;
    }

    public function save()
    {
        $this->validate();

        if ($this->userId) {
            $user = User::find($this->userId);
            if ($user) {
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'username' => $this->username,
                    'is_active' => (bool) $this->is_active,
                    'is_approved' => (bool) $this->is_approved,
                ]);
            }
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'is_active' => (bool) $this->is_active,
                'is_approved' => (bool) $this->is_approved,
                'password' => bcrypt('password'),
            ]);
        }

        $this->dispatchTo('users-table', 'refreshUsers');
        $this->dispatch('close-user-modal');
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}

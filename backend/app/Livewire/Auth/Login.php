<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Login - EVM Dashboard')]
class Login extends Component
{
    public $username = '';
    public $password = '';

    public function login()
    {
        // Simulate login and redirect to dashboard
        return redirect()->to('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth');
    }
}

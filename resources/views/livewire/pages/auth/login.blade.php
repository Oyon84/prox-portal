<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Http;
use App\Services\ProxmoxAuthService;

new class extends Component
{
    public LoginForm $form;

    public $realm = [];

    /**
     * Handle an incoming authentication request.
     */

    public function login(ProxmoxAuthService $proxmoxAuth): void
    {
        $this->validate();

        $this->form->authenticate();

        try {
            $proxmoxAuth->authenticate(Auth::user()->pveUsername, $this->form->password, 'pve');
        } catch(ErrorException) {
            
            session()->invalidate();

            session()->flash('failed', 'PVE Authentication failed, please contact the support team.');

            redirect()->route('login');
        }

        Session::regenerate();

        Session::flash('status', 'Successfully logged in');

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    public function getDomains()
    {
        $domains = Http::withoutVerifying()->get(env('PROXMOX_BASE_URI') . '/api2/json/access/domains');

        foreach ($domains['data'] as $d => $data) {
            if (empty($data['comment'])) {
                $data['comment'] = $data['realm']; 
            }
            $this->realm[$data['realm']] = $data['comment'];
        }
        return $this->realm;
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-flash-error :messages="$errors->get('form.email')" class="mt-2" />
    <x-partials.guest-branding />
    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Realm -->
        <div class="mt-4">
            <x-input-label for="realm" :value="__('Realm')" />
            <select wire:model="form.realm" name="realm" id="realm"
                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option selected value> -- select a realm -- </option>
            @foreach ($this->getDomains() as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
            </select>
            <x-input-error :messages="$errors->get('form.realm')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('password.request') }}" wire:navigate>
                {{ __('Forgot your password?') }}
            </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    <x-partials.guest-footer />
</div>
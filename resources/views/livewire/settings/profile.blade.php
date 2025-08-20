<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Livewire\Volt\Component;

new class extends Component {
    // membuat class ini sebagai Livewire component
    // dan mengaktifkan fitur upload file
    use WithFileUploads;


    public string $name = '';
    public string $email = '';
    public $photo;
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */

    // membuat fungsi untuk mengupdate password
    // validasi password saat ini, password baru, dan konfirmasi password
    public function updatePassword(): void
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password'])
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->dispatch('password-updated');
    }

    // membuat fungsi untuk mengupdate foto profil
    // validasi foto harus ada, harus berupa gambar, dan ukuran maksimal 1MB
    public function updatePhoto(): void
    {
        $validated = $this->validate([
            'user_photo' => ['required', 'image', 'max:1024'], // max 1MB
        ]);

        $user = Auth::user();
        
        if ($user->photo) {
            Storage::delete($user->photo);
        }

        $path = $this->photo->store('profile-photos');
        
        $user->update([
            'user_photo' => $path
        ]);

        $this->dispatch('photo-updated');
    }



    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your profile information')">
        <!-- Existing profile form -->
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <!-- ...existing form fields... -->
        </form>

        <!-- Photo Upload Form -->
        <form wire:submit="updatePhoto" class="mt-6 border-t pt-6">
            <h2 class="text-lg font-medium">{{ __('Profile Photo') }}</h2>
            
            <div class="mt-4 space-y-4">
                @if(auth()->user()->photo)
                    <div class="mb-4">
                        <img src="{{ Storage::url(auth()->user()->photo) }}" 
                             alt="Profile photo" 
                             class="w-20 h-20 rounded-full">
                    </div>
                @endif

                <flux:input type="file" wire:model="photo" accept="image/*" />
                
                <div class="flex items-center gap-4">
                    <flux:button type="submit" variant="primary">
                        {{ __('Update Photo') }}
                    </flux:button>

                    <x-action-message on="photo-updated">
                        {{ __('Photo updated.') }}
                    </x-action-message>
                </div>
            </div>
        </form>

        <!-- Password Update Form -->
        <form wire:submit="updatePassword" class="mt-6 border-t pt-6">
            <h2 class="text-lg font-medium">{{ __('Update Password') }}</h2>
            
            <div class="mt-4 space-y-4">
                <flux:input type="password" 
                           wire:model="current_password"
                           :label="__('Current Password')"
                           required />

                <flux:input type="password"
                           wire:model="password"
                           :label="__('New Password')"
                           required />

                <flux:input type="password"
                           wire:model="password_confirmation"
                           :label="__('Confirm Password')"
                           required />

                <div class="flex items-center gap-4">
                    <flux:button type="submit" variant="primary">
                        {{ __('Update Password') }}
                    </flux:button>

                    <x-action-message on="password-updated">
                        {{ __('Password updated.') }}
                    </x-action-message>
                </div>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>

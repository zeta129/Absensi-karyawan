<section>
    <header>
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            ✏️ {{ __('Profile Information') }}
        </h2>

        <p class="mt-2 text-sm text-gray-600">
            @if(Auth::user()->isAdmin())
                {{ __("Update your account's profile information and email address.") }}
            @else
                {{ __("View your profile information. Admins can edit profiles in Employee Management.") }}
            @endif
        </p>
    </header>

    @if(!Auth::user()->isAdmin())
    <div class="mt-4 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded">
        <p class="font-semibold">📌 View Only</p>
        <p class="text-sm mt-1">Your profile can only be edited by administrators. To request changes, please contact your admin.</p>
    </div>
    @endif

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="input-modern mt-2 block w-full" :value="old('name', $user->name)" {{ !Auth::user()->isAdmin() ? 'disabled' : '' }} required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="input-modern mt-2 block w-full" :value="old('email', $user->email)" {{ !Auth::user()->isAdmin() ? 'disabled' : '' }} required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        ⚠️ {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="text-purple-600 hover:text-purple-800 font-semibold">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            ✅ {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if(Auth::user()->isAdmin())
        <div class="flex items-center gap-4">
            <x-primary-button class="btn-primary">{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-semibold"
                >✅ {{ __('Saved.') }}</p>
            @endif
        </div>
        @endif
    </form>
</section>

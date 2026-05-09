<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_mot_de_passe" :value="__('Mot de passe actuel')" />
            <x-text-input id="update_password_current_mot_de_passe" name="current_mot_de_passe" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_mot_de_passe')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_mot_de_passe" :value="__('Nouveau mot de passe')" />
            <x-text-input id="update_password_mot_de_passe" name="mot_de_passe" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('mot_de_passe')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_mot_de_passe_confirmation" :value="__('Confirmer le mot de passe')" />
            <x-text-input id="update_password_mot_de_passe_confirmation" name="mot_de_passe_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('mot_de_passe_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

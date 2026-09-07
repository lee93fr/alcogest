<x-guest-layout>

    {{-- Message si redirigé depuis le catalogue --}}
    @if(request()->is('login') && url()->previous() && str_contains(url()->previous(), 'catalogue'))
    <div class="mb-4 rounded-xl bg-indigo-50 border border-indigo-200 px-4 py-3 text-indigo-800 text-sm">
        Connectez-vous pour accéder au catalogue.
    </div>
    @endif

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8">
        <p class="brand-eyebrow mb-3">Heureux de vous revoir</p>
        <h1 class="display-font text-4xl text-wine-950">Connexion</h1>
        <p class="text-sm text-stone-500 mt-2 leading-6">Accédez à votre catalogue, vos commandes et votre espace de gestion.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full" type="password"
                          name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-stone-300 text-wine-700 shadow-sm focus:ring-wine-600"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600">Se souvenir de moi</span>
            </label>
        </div>

        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-4 mt-7">
            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-stone-500 hover:text-wine-700 rounded-md transition-colors"
                   href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
            <x-primary-button class="w-full sm:w-auto">Se connecter <span class="ml-2">→</span></x-primary-button>
        </div>
    </form>
</x-guest-layout>


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'La Tournée!') }}</title>
        <meta name="theme-color" content="#421321">
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased guest-shell">
        <div class="min-h-screen grid lg:grid-cols-[1.05fr_.95fr]">
            <section class="guest-brand-panel hidden lg:flex flex-col justify-between p-12 xl:p-16 text-white">
                <a href="/" class="relative z-10 flex items-center gap-3 w-fit">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/15 backdrop-blur"><img src="/images/logo-la-tournee.svg" alt="" class="h-9 w-9 brightness-0 invert"></span>
                    <span class="brand-wordmark text-3xl">La Tournée!</span>
                </a>
                <div class="relative z-10 max-w-xl pb-10">
                    <p class="brand-eyebrow mb-5">La sélection qui rassemble</p>
                    <h1 class="display-font text-5xl xl:text-6xl leading-[1.05]">Vos boissons préférées, livrées avec soin.</h1>
                    <p class="mt-6 max-w-lg text-base leading-7 text-white/70">Un catalogue pensé pour les professionnels, des commandes simples et un suivi clair à chaque étape.</p>
                    <div class="mt-10 flex gap-8 text-sm text-white/65">
                        <span><strong class="block text-2xl text-white display-font">Simple</strong>à commander</span>
                        <span><strong class="block text-2xl text-white display-font">Local</strong>et réactif</span>
                        <span><strong class="block text-2xl text-white display-font">Fiable</strong>au quotidien</span>
                    </div>
                </div>
                <p class="relative z-10 text-xs text-white/40">L'abus d'alcool est dangereux pour la santé. À consommer avec modération.</p>
            </section>
            <section class="guest-form-panel flex min-h-screen items-center justify-center p-4 sm:p-8">
                <div class="w-full max-w-md">
                    <a href="/" class="mb-7 flex items-center justify-center gap-2 lg:hidden"><img src="/images/logo-la-tournee.svg" alt="La Tournée" class="h-10 w-10"><span class="brand-wordmark text-2xl text-wine-900">La Tournée!</span></a>
                    <div class="guest-card">{{ $slot }}</div>
                    <p class="mt-6 text-center text-xs text-stone-400">Une consommation responsable, un service professionnel.</p>
                </div>
            </section>
        </div>
    </body>
</html>


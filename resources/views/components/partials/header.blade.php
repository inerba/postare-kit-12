<div class="text-muted bg-neutral-100 p-12 text-center">
    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
        <div class="flex text-4xl uppercase lg:col-start-2 lg:justify-center">Header</div>
        <nav class="-mx-3 flex flex-1 justify-end">
            @auth
                <a
                    href="{{ \Filament\Facades\Filament::getCurrentPanel()->getUrl() }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Dashboard
                </a>
            @else
                <a
                    href="{{ \Filament\Facades\Filament::getCurrentPanel()->getLoginUrl() }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Log in
                </a>
            @endauth
        </nav>
    </header>
</div>

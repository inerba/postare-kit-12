{{--
    Per modificare lo stile dei bottoni, modificare il file app\Mason\Macro\ButtonsRepeater.php
--}}
<div class="not-prose mt-12 flex items-center justify-center gap-6">
    @foreach ($buttons as $button)
        <a
            href="{{ $button['button_link'] }}"
            target="{{ $button['button_target'] }}"
            @class([
                'rounded-md leading-none',
                match ($button['class']) {
                    'border' => 'hover:bg-quaternary border-2 border-black bg-transparent text-black',
                    'primary' => 'bg-primary hover:bg-primary-700 text-white',
                    'secondary' => 'bg-secondary hover:bg-secondary/80 text-white',
                    'tertiary' => 'bg-tertiary hover:bg-tertiary/80 text-white',
                    'quaternary' => 'bg-quaternary hover:bg-quaternary/80 text-white',
                    'accent' => 'bg-accent hover:bg-accent-700 text-white',
                    default => null,
                },
                match ($button['button_size']) {
                    'sm' => 'p-2 text-sm',
                    'lg' => 'p-2.5 text-lg',
                    'xl' => 'p-3 text-xl',
                    '2xl' => 'p-4 text-2xl',
                    default => 'p-2 text-base',
                },
            ])
        >
            {{ $button['button_text'] }}
        </a>
    @endforeach
</div>

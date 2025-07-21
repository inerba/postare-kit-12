@php
    $sliderHeight = match ($settings['height'] ?? null) {
        'small' => 'aspect-square lg:aspect-[3/1]',
        'medium' => 'aspect-square lg:aspect-[7/3]',
        'large' => 'aspect-square lg:aspect-[16/9]',
        'full' => 'h-screen',
        default => 'aspect-square lg:aspect-[16/9]',
    };

    $slides = collect($slides ?? []);
@endphp

<div class="not-prose relative w-full shadow-xl" x-data="{
    splide: null,
    currentSlide: 0,
    videos: [],
    autoplayTimer: null,
    progressInterval: null,
    progress: 0,
    isPaused: false,
    slideDurations: @js($slides->map(fn($slide) => $slide['duration'] ?? 5)->toArray()),
    init() {
        this.splide = new Splide(this.$refs.splide, {
            perPage: {{ $perPage }},
            pagination: {{ $pagination }},
            arrows: {{ $arrows }},
            rewind: {{ $rewind }},
            // autoHeight: true,
            gap: '{{ $carouselGap }}',
            breakpoints: {
                640: {
                    perPage: 1,
                },
            },
            autoplay: false, // Disabilita l'autoplay nativo, lo gestiremo noi
        }).mount()

        // Memorizza i riferimenti ai video all'inizio per ottimizzare
        this.videos = Array.from(
            this.$refs.splide.querySelectorAll('.splide__slide video'),
        ).map((video) => ({
            element: video,
            index: parseInt(video.closest('.splide__slide').dataset.index),
        }))

        this.splide.on('move', (newIndex) => {
            this.currentSlide = newIndex
            // Riavvia il timer con la durata della nuova slide
            this.startAutoplayTimer()
        })

        this.$watch('currentSlide', () => {
            this.playPauseVideo()
        })

        // Autoplay video on the first slide if it's a video
        this.playPauseVideo()

        // Avvia il timer per la prima slide
        this.startAutoplayTimer()
    },
    startAutoplayTimer() {
        // Cancella il timer precedente se esiste
        if (this.autoplayTimer) {
            clearTimeout(this.autoplayTimer)
            this.autoplayTimer = null
        }

        // Cancella l'intervallo di progresso precedente se esiste
        if (this.progressInterval) {
            clearInterval(this.progressInterval)
            this.progressInterval = null
        }

        // Reimposta il progresso a 0
        this.progress = 0

        // Non avviare un nuovo timer se lo slider è in pausa
        if (this.isPaused) {
            return
        }

        // Ottieni la durata per la slide corrente (in secondi)
        const duration = (this.slideDurations[this.currentSlide] || 5) * 1000

        // Configura l'intervallo per aggiornare la percentuale
        const updateInterval = 50 // Aggiorna ogni 50ms per un'animazione fluida
        const totalSteps = duration / updateInterval
        let currentStep = 0

        this.progressInterval = setInterval(() => {
            currentStep++
            {{-- this.progress = Math.min(Math.round((currentStep / totalSteps) * 100), 100) --}}
            this.progress = Math.min((currentStep / totalSteps) * 100), 100

            // Ferma l'intervallo quando raggiungiamo il 100%
            if (this.progress >= 100) {
                clearInterval(this.progressInterval)
            }
        }, updateInterval)

        // Imposta un nuovo timer
        this.autoplayTimer = setTimeout(() => {
            // Vai alla prossima slide
            if (this.currentSlide < this.slideDurations.length - 1) {
                this.splide.go('+1')
            } else {
                this.splide.go(0) // Torna all'inizio se siamo all'ultima slide
            }
        }, duration)

        {{-- console.log(`Timer impostato per ${duration}ms`, this.currentSlide) --}}
    },
    togglePlayPause() {
        this.isPaused = !this.isPaused

        {{-- console.log('Toggle stato:', this.isPaused ? 'Pausa' : 'Riproduci') --}}

        if (this.isPaused) {
            // Ferma il timer se in pausa
            if (this.autoplayTimer) {
                clearTimeout(this.autoplayTimer)
                this.autoplayTimer = null
            }

            // Ferma l'intervallo di aggiornamento della percentuale
            if (this.progressInterval) {
                clearInterval(this.progressInterval)
                this.progressInterval = null
            }
        } else {
            // Riavvia il timer se non più in pausa
            this.startAutoplayTimer()

            // Vai alla prossima slide
            this.splide.go('+1')
        }
    },
    playPauseVideo() {
        this.videos.forEach(({ element, index }) => {
            if (index !== this.currentSlide) {
                element.pause()
                element.currentTime = 0 // Riavvolgi il video
            } else {
                // Controllo se il video è caricato prima di riprodurlo
                if (element.readyState >= 2) {
                    element
                        .play()
                        .catch(() =>
                            console.log('Riproduzione video non riuscita'),
                        )
                } else {
                    element.addEventListener(
                        'canplay',
                        () => {
                            element
                                .play()
                                .catch(() =>
                                    console.log(
                                        'Riproduzione video non riuscita',
                                    ),
                                )
                        }, { once: true },
                    )
                }
            }
        })
    },
}">
    <section x-ref="splide" class="splide" aria-label="Galleria di immagini e video">
        <div class="splide__track">
            <ul class="splide__list">
                @foreach ($slides as $slide)
                    <li class="splide__slide relative" data-index="{{ $loop->index }}">
                        @if ($slide['is_video'])
                            <video @if ($loop->first) autoplay @endif preload="metadata" muted loop
                                playsinline loading="lazy" class="{{ $sliderHeight }} w-full object-cover object-center"
                                aria-label="Video {{ $loop->index + 1 }}">
                                <source src="/storage/{{ $slide['video_mp4'] }}" type="video/mp4" />
                                @if ($slide['video_webm'])
                                    <source src="/storage/{{ $slide['video_webm'] }}" type="video/webm" />
                                @endif
                                Il tuo browser non supporta il tag video.
                            </video>
                        @else
                            <img src="/storage/{{ $slide['image'] }}"
                                alt="{{ $slide['alt'] ?? 'Immagine slide ' . ($loop->index + 1) }}" loading="lazy"
                                class="{{ $sliderHeight }} w-full object-cover" />
                        @endif

                        @if ($slide['title'] || $slide['content'])
                            <div x-data="{ show: true }">
                                <div x-show="show" x-transition.opacity.duration.500ms
                                    class="bg-logo1/35 absolute inset-0"></div>
                                <div x-show="show" x-transition:enter.delay.500ms @class([
                                    'absolute inset-0 mx-auto flex items-center justify-center p-6 text-white',
                                    match ($slide['width'] ?? null) {
                                        'small' => 'max-w-sm',
                                        'medium' => 'max-w-3xl',
                                        'large' => 'max-w-5xl',
                                        default => 'max-w-3xl',
                                    },
                                ])>
                                    <div
                                        class="hover:bg-logo1/30 bg-logo1/10 relative flex flex-1 flex-col items-start gap-2 rounded-3xl p-8 shadow-2xl backdrop-blur-md transition-all duration-700 hover:backdrop-blur-2xl">
                                        @if ($slide['title'])
                                            <h2 class="text-xl font-bold lg:text-3xl">{{ $slide['title'] }}</h2>
                                        @endif

                                        @if ($slide['subtitle'])
                                            <p class="lg:text-xl">{{ $slide['subtitle'] }}</p>
                                        @endif

                                        @if ($slide['content'])
                                            <div class="mt-4 lg:text-lg">{!! $slide['content'] !!}</div>
                                        @endif

                                        @if ($slide['button_text'] && $slide['button_link'])
                                            <a href="{{ $slide['button_link'] }}"
                                                class="bg-accent hover:bg-accent-600 animate-bounce-loop mt-4 flex w-auto items-center rounded px-4 py-2 text-lg font-bold text-white transition-all">
                                                {{ $slide['button_text'] }}

                                                @svg('heroicon-o-arrow-right', 'ml-4 h-5 w-5')
                                            </a>
                                        @endif

                                        <button type="button"
                                            class="absolute right-3 top-3 text-white/30 hover:text-white"
                                            @click="show=!show">
                                            @svg('heroicon-o-x-mark', 'size-5')
                                        </button>
                                    </div>

                                    <template x-teleport="#commands">
                                        <button x-show="! show && currentSlide === {{ $loop->index }}"
                                            @click="show=true" type="button"
                                            class="rounded bg-gray-800/50 p-2 text-xs text-white/70 transition-all hover:bg-gray-800"
                                            aria-label="Mostra informazioni slide {{ $loop->index + 1 }}">
                                            <span class="flex items-center gap-2">
                                                @svg('heroicon-o-information-circle', ' size-5')
                                                <span class="hidden md:inline">
                                                    Mostra informazioni slide {{ $loop->index + 1 }}
                                                </span>
                                            </span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        <div id="commands" class="absolute bottom-4 right-4 flex gap-2"></div>
        <template x-teleport="#commands">
            <div class="flex items-center gap-2">
                {{--
                    <div
                    class="z-10 rounded bg-gray-800/50 px-3 py-2 font-mono text-xs text-white/70"
                    x-text="`${progress}%`"
                    ></div>
                --}}
                <button @click="togglePlayPause()"
                    class="z-10 cursor-pointer rounded bg-gray-800/50 px-2 py-2 text-xs text-white/70 transition-all hover:bg-gray-800"
                    type="button" aria-label="Pausa/Riprendi presentazione">
                    <template x-if="!isPaused">
                        <span class="flex items-center gap-2">
                            @svg('heroicon-o-pause', ' size-5')
                            <span class="hidden md:inline">Pausa presentazione</span>
                        </span>
                    </template>
                    <template x-if="isPaused">
                        <span class="flex items-center gap-2">
                            @svg('heroicon-o-play', ' size-5')
                            <span class="hidden md:inline">Riprendi presentazione</span>
                        </span>
                    </template>
                </button>
            </div>
        </template>
        <div x-show="!isPaused" class="absolute z-10 w-full">
            <div class="bg-accent/80 h-1 transition-all" :style="`width: ${progress}%`"></div>
        </div>
    </section>

    {{-- <div class="bg-logo1 h-2.5 w-full"></div> --}}
    {{-- <div class="bg-logo2 h-2.5 w-full"></div> --}}
</div>
{{--  --}}
@pushOnce('scripts', 'splide-script')
    @vite(['resources/js/splide.js'])
@endpushOnce


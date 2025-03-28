<div class="mt-12 flex items-center justify-center gap-6">
    @foreach ($buttons as $button)
        <x-button
            :href="$button['button_link']"
            :text="$button['button_text']"
            :target="$button['button_target']"
            :class="$button['class']"
        />
    @endforeach
</div>

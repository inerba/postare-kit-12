@props([
    'content',
])

<div>
    {!! mason($content, \App\Mason\BrickCollection::make())->toHtml() !!}
</div>

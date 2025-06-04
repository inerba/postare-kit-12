@props([
    'data' => null,
    'content',
])

<div>
    {!! mason($content, \App\Mason\Collections\PageBrickCollection::make())->toHtml() !!}
</div>

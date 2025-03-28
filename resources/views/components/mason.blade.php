@aware(['post'])

<div>
    {!! mason($post->content, \App\Mason\BrickCollection::make())->toHtml() !!}
</div>

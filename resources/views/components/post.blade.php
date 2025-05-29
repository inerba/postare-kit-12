@aware(['post'])

<div>
    {!! mason($post->content, \App\Mason\Collections\PostBrickCollection::make())->toHtml() !!}
</div>

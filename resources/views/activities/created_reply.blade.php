@component('activities.partials.activity')
    @slot('heading')
        {{ $activity->subject->owner->name }} replied on
        <a href="{{ route('thread.show', ['channel' => $activity->subject->thread->channel->slug, 'thread' => $activity->subject->thread->slug]) }}">
            '{{ $activity->subject->thread->title }}'
        </a>
    @endslot

    @slot('body')
        <p class="lead">{!! Markdown::convertToHtml($activity->subject->body) !!}</p>
    @endslot
@endcomponent

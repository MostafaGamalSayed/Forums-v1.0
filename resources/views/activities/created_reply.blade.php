@component('activities.partials.activity')
    @slot('heading')
        {{ $activity->subject->owner->name }} replied on
        <a href="{{ route('thread.show', ['channel' => $activity->subject->thread->channel->slug, 'thread' => $activity->subject->thread->id]) }}">
            '{{ $activity->subject->thread->title }}'
        </a>
    @endslot

    @slot('body')
        <p class="lead">{{ $activity->subject->body }}</p>
    @endslot
@endcomponent

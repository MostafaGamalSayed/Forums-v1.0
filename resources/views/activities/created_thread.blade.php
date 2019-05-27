@component('activities.partials.activity')
    @slot('heading')
        {{ $activity->subject->owner->name }} posted
        <a href="{{ route('thread.show', ['channel' => $activity->subject->channel->slug, 'thread' => $activity->subject->id]) }}">
            '{{ $activity->subject->title }}'
        </a>
    @endslot

    @slot('body')
        <h5 class="card-title text-muted">{{ $activity->subject->title }}</h5>
        <p class="card-text lead">{{ $activity->subject->body }}</p>
    @endslot
@endcomponent


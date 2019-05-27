@component('activities.partials.activity')
    @slot('heading')
        {{ $user->name }} favorited a
        <a href="{{ route('thread.showReply',
         ['channel' => $activity->subject->favoritable->thread->channel->slug,
          'thread'  => $activity->subject->favoritable->thread->id,
          'reply'   => $activity->subject->favoritable->id
          ]
         ) }}">
            reply
        </a>
    @endslot

    @slot('body')
        <p class="lead">{{ $activity->subject->favoritable->body }}</p>
    @endslot
@endcomponent
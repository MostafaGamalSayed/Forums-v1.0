<reply :attributes="{{ $reply }}" thread="{{ $reply->thread->id }}" inline-template v-cloak>
    <div class="card mt-3" id="reply-{{ $reply->id }}">
        <div class="card-header">
            <h6 class="d-inline"><a href="{{ route('user.profile', $reply->owner->name) }}">{{ $reply->owner->name }}</a> replied {{ $reply->created_at->diffForHumans() }}</h6>
            @can('delete', $reply)
            <button type="submit" @click="destroy" class="float-right bg-transparent border-0" style="cursor: pointer">
            <i class="fas fa-trash-alt text-danger"></i>
            </button>
            @endcan
            @can('update', $reply)
            <button type="submit" @click="editing = true" class="float-right bg-transparent border-0" style="cursor: pointer">
            <i class="fas fa-edit text-muted"></i></i>
            </button>
            @endcan
        </div>

        <div class="card-body">
            <div v-if="editing">
                <textarea class="form-control" v-model="body"></textarea>
                <div class="btn-group btn-group-xs mt-3">
                    <button class="btn btn-primary d-inline" @click="update">Edit</button>
                    <button class="d-inline btn btn-link" @click="editing=false">cancel</button>
                </div>
            </div>
            <div v-else>
                <p class="card-text" v-text="body"></p>
                @auth
                <div>
                    <favorite :reply="{{ $reply }}"></favorite>
                </div>
                @endauth
            </div>
        </div>
    </div>
</reply>

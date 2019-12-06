<template>
<div>
    <!-- <div class="card mt-3" :id="'reply-' + reply.id">
        <div class="card-header">
            <h6 class="d-inline"><a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name"></a> replied <span v-text="ago"></span></h6>
            <div v-if="signedIn" class="d-inline">
                <div v-if="canDelete" class="d-inline">
                    <button type="submit" @click="destroy" class="float-right bg-transparent border-0" style="cursor: pointer">
                        <i class="fas fa-trash-alt text-danger"></i>
                    </button>
                </div>

                <div v-if="canUpdate" class="d-inline">
                    <button type="submit" @click="editing = true" class="float-right bg-transparent border-0" style="cursor: pointer">
                        <i class="fas fa-edit text-muted"></i></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <textarea class="form-control" v-model="body"></textarea>
                <div class="btn-group btn-group-xs mt-3">
                    <button class="btn btn-primary d-inline" @click="update">Edit</button>
                    <button class="d-inline btn btn-link" @click="cancelUpdate">cancel</button>
                </div>
            </div>
            <div v-else>
                <p class="card-text" v-html="markdown"></p>
                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>
        </div>
    </div> -->


    <div class="media media-comment" :id="'reply-' + reply.id">
        <img alt="Image placeholder" class="media-comment-avatar rounded-circle" :src="reply.owner.avatarFullPath">
        <div class="media-body">
            <div class="media-comment-text" :class="bestReplySuccessBorder">
                <h6 class="h5 mt-0 mb-0"><a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name"></a></h6>
                <small v-if="!editing"><i class="fa fa-clock mr-1"></i>Replied {{ reply.ago }}</small>
                <div v-if="editing">
                    <textarea class="form-control" v-model="body"></textarea>
                    <div class="btn-group btn-group-xs mt-3">
                        <button class="btn btn-primary d-inline" @click="update">Edit</button>
                        <button class="d-inline btn btn-link" @click="cancelUpdate">cancel</button>
                    </div>
                </div>
                <div v-else>
                    <p class="text-sm lh-160" v-html="markdown">
                    </p>
                    <div class="media-footer">
                        <favorite :reply="reply"></favorite>

                        <span v-if="signedUser && canUpdate">
                            |
                            <button type="submit" @click="editing = true" class="text-muted bg-transparent border-0" style="cursor: pointer">
                                <small>
                                    <i class="fas fa-edit mr-1"></i></i>
                                    Edit
                                </small>
                            </button>
                        </span>

                        <span v-if="signedUser && canDelete">
                            |
                            <button type="submit" @click="destroy" class="text-muted bg-transparent border-0" style="cursor: pointer">
                                <small>
                                    <i class="fas fa-trash-alt"></i>
                                    Delete
                                </small>
                            </button>
                        </span>
                        <span v-if="signedUser && canUpdateThread">
                            <button v-show="!bestAnswer" type="submit" @click="markAsBestReply" class="btn btn-link btn-sm text-muted float-right" style="cursor: pointer">
                                Best Answer ?
                            </button>
                        </span>
                        <span v-show="bestAnswer" class="badge badge-success p-2 float-right">
                            <i class="fa fa-check-circle mr-1"></i>
                            The Best Answer
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import favorite from './favorite.vue';
import moment from 'moment';

export default {

    props: [
            'data',
            'thread'
        ],

    components: {
        favorite
    },
    data() {
        return {
            reply: this.data,
            editing: false,
            body: this.data.body,
            markdown: this.data.bodyMarkDown,
            signedUser: window.App.signedIn,
            bestAnswer: this.data.isBestReply
        }
    },
    computed: {
        ago() {
            return moment(this.data.created_at).fromNow();
        },
        signedIn() {
            return window.App.signedIn;
        },
        canUpdate() {
            return this.authorize(user => this.data.user_id == user.id);
        },
        canDelete() {
            return this.authorize(user => this.data.user_id == user.id);
        },
        canUpdateThread() {
            return this.authorize(user => this.data.thread.user_id == user.id);
        },
        bestReplySuccessBorder() {
            return this.bestAnswer ? 'border border-success' : 'border-0';
        }
    },
    methods: {
        update() {
            axios.patch('/threads/' + this.thread + '/replies/' + this.data.id, {
                body: this.body
            }).then((response) => {
                this.editing = false;

                this.reply = response.data;

                this.markdown = response.data.bodyMarkDown;

                flash('The reply has been updated!');
            }).catch(error => {
                flash(error.response.data, 'danger');
            });
        },
        cancelUpdate() {
            this.editing = false;

            this.body = this.reply.body;
        },
        destroy() {
            axios.delete('/threads/' + this.thread + '/replies/' + this.data.id);
            this.$emit('deleted', this.data.id);
            flash('The reply has been deleted');
        },
        markAsBestReply() {
            axios.post('/replies/' + this.reply.id + '/best').then((response) => {
                this.bestAnswer = true;

                window.events.$emit('best-reply-selected', this.data.id);

                flash('You marked this reply as the best answer.');
            });
        }
    },
    created() {
        $('table').addClass('table table-bordered');
        $('thead').addClass('bg-default text-white');
        window.events.$on('best-reply-selected', id => {
            console.log(this.data.id == id);
            this.bestAnswer = (this.data.id == id);
        });
    }
}
</script>

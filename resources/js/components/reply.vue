<template>
<div>
    <div class="card mt-3" :id="'reply-' + reply.id">
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
                <p class="card-text" v-html="body"></p>
                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
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
            body: this.data.body
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
    },
    methods: {
        update() {
            axios.patch('/threads/' + this.thread + '/replies/' + this.data.id, {
                body: this.body
            }).then(() => {
                this.editing = false;

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

        }
    },
}
</script>

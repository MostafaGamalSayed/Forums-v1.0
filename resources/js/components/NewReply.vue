<template>
<div v-if="signedIn">
    <div class="media align-items-center mt-5">
        <img alt="Image placeholder" class="avatar avatar-lg rounded-circle mb-4" :src="signedUserAvatar">
        <div class="media-body">
            <textarea class="form-control" id="thread-reply" placeholder="Can you help?" v-model="body" rows="3"></textarea>
            <small v-if="errorHas('body')" class="text-danger" role="alert">
                {{ errorMessages['body'][0] }}
            </small>
        </div>
    </div>
    <button type="submit" class="btn btn-primary float-right mr-2" @click="addReply">Reply</button>
</div>
</template>

<script>
import 'jquery.caret';
import 'at.js';

export default {
    data() {
        return {
            body: '',
            endpoint: location.pathname + '/replies',
            errorMessages: [],
            signedIn: window.App.signedIn
        }
    },
    methods: {
        addReply() {
            axios.post(this.endpoint, {

                    body: this.body

                })
                .then(({
                    data
                }) => {
                    // Clear the input
                    this.body = '';

                    // Clear the error messages
                    this.errorMessages = [];

                    // send a message to the user
                    flash('Your reply has been posted.');

                    // push the new reply to the replies list
                    this.$emit('ReplyCreated', data);

                }).catch(error => {
                    this.errorMessages = [];
                    if (error.response.status == 422) {
                        this.errorMessages = error.response.data.errors;
                    } else {
                        flash(error.response.data, 'danger')
                    }

                });

        },
        errorHas(key) {
            // Return true if the error messages has the key parameter otherwise it will return false
            return this.errorMessages.hasOwnProperty(key);
        }
    },
    mounted() {
        $('#thread-reply').atwho({
            at: "@",
            delay: 750,
            callbacks: {
                remoteFilter: function (query, callback) {
                    $.getJSON("/api/users", {
                        name: query
                    }, function (usernames) {
                        callback(usernames)
                    });
                }
            }
        });
    },
    computed: {
        signedUserAvatar() {
            return window.App.user.avatarFullPath;
        }
    },
}
</script>

<template>
<div>
    <div class="form-group mt-5">
        <textarea name="body" v-model="body" class="form-control" id="thread-reply" rows="4" placeholder="Can you help? leave your reply here."></textarea>
        <small v-if="errorHas('body')" class="text-danger" role="alert">
            {{ errorMessages['body'][0] }}
        </small>
    </div>
    <button type="submit" class="btn btn-primary" @click="addReply">Reply</button>
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
            errorMessages: []
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
}
</script>

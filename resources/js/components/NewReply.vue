<template>
<div v-if="signedIn">
    <div class="media align-items-center mt-5">
        <img alt="Image placeholder" class="avatar avatar-lg rounded-circle mb-4" :src="signedUserAvatar">
        <div class="media-body">
            <text-editor name="body" @TrixChanged="syncWithBody" v-model="body"></text-editor>
            <small v-if="errorHas('body')" class="text-danger" role="alert">
                {{ errorMessages['body'][0] }}
            </small>
        </div>
    </div>
    <button type="submit" class="btn btn-primary float-right mt-3" @click="addReply">Reply</button>
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
                    $('#trix').val('');
                    $('trix-editor').empty();

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
        },
        // syncWithBody(data) {
        //     console.log(data.indexOf('<pre>'));
        //     this.body = data;
        // }

        syncWithBody(data) {
            let pureCode = this.getPureCode(data);

            let final_body = data.replace("<pre>" + pureCode + "</pre>", "<pre><code>" + pureCode + "</code></pre>")
            console.log(final_body);
            this.body = final_body;
        },
        getPureCode(data) {
            let preTagStart = data.indexOf('<pre>');
            let purCodeStart = preTagStart + 5;
            let preTagEnd = data.indexOf('</pre>');
            let purCodeEnd = preTagEnd;
            let length = purCodeEnd - purCodeStart;
            return data.substr(purCodeStart, length);
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

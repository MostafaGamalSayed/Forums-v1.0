<template>
        <div>
            <div class="form-group mt-5">
                <textarea name="body" v-model="body"  class="form-control" id="thread-reply" rows="4" placeholder="Can you help? leave your reply here."></textarea>
            </div>
            <button type="submit" class="btn btn-primary" @click="addReply">Reply</button>
        </div>
</template>

<script>
    export default {
        data(){
            return{
                body: '',
                endpoint: location.pathname + '/replies'
            }
        },
        methods:{
            addReply(){
                axios.post(this.endpoint, {body: this.body})
                     .then(({data}) => {
                        // Clear the input
                        this.body = '';
                        // send a message to the user
                        flash('Your reply has been posted.');
                        // push the new reply to the replies list
                        this.$emit('ReplyCreated', data);
                     });

            }
        },
    }
</script>
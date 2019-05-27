<template>
    <div>
        <div class="card">
            <div class="card-body">
                <p class="text-muted font-weight-bold">
                    Subscribe to receive notifications for any update on this thread
                </p>
                <hr>
                <button :class="classes" @click="toggle">
                    <span v-show="isSubscribed" v-html="rawHtml"></span>
                    <span v-text="status"></span>
                </button>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['thread'],
        data(){
            return{
                isSubscribed: this.thread.isSubscribed,
                rawHtml: '<i class="fas fa-check-circle mr-1"></i>',
                endpoint: location.pathname + '/subscription',
            }
        },
        computed:{
            classes(){
                return ['btn btn-block', this.isSubscribed ? 'btn-primary' : 'btn-outline-primary'];
            },
            status(){
                return this.isSubscribed ? 'Subscribed' : 'subscribe ?'
            },
        },
        methods:{
            toggle(){
                this.isSubscribed ? this.unSubscribe() : this.subscribe();
            },
            subscribe(){
                axios.post(this.endpoint).then(this.refresh).catch((error) => this.handle(error));
            },
            unSubscribe(){
                axios.delete(this.endpoint).then(this.refresh).catch((error) => this.handle(error));
            },
            refresh(response){

                this.isSubscribed = !this.isSubscribed;
                flash('Your subscription list has been updated.');
            },
            handle(error){
                if(error.response.status === 401){
                    window.location.href = '/login';
                }
            }
        },
    }
</script>
<template>
    <div class="container">
        <div class="alert alert-success flash-message" role="alert" v-show="show">
            <i class="fas fa-check-circle mr-1"></i><strong>Success! </strong>{{ body }}
        </div>
    </div>
</template>

<script>
    export default {
        props:['message'],

        data(){
            return {
                body: '',
                show: false
            }
        },
        created(){
            if(this.message){
                this.flash(this.message);
            }
        window.events.$on('flash', message => this.flash(message));
        },
        methods:{
            flash(message){
                this.body = message;
                this.show = true;

                this.hide()
            },

            hide(){
                setTimeout(() => {
                    this.show = false
                }, 3000);
            }
        }
    };
</script>

<style>
    .flash-message{
        position: fixed;
        right: 25px;
        bottom: 40px;
    }
</style>

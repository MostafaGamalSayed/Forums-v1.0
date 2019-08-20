<template>
<div class="container">
    <div class="alert flash-message" :class="'alert-' + type" role="alert" v-show="show">
        <i class="fas fa-check-circle mr-1"></i> {{ body }}
    </div>
</div>
</template>

<script>
export default {
    props: ['data'],

    data() {
        return {
            body: '',
            type: '',
            show: false
        }
    },
    created() {
        if (this.data) {
            this.flash(data);
        }
        window.events.$on('flash', data => this.flash(data));
    },
    methods: {
        flash(data) {
            this.body = data.message;
            this.type = data.type;
            this.show = true;

            this.hide();
        },

        hide() {
            setTimeout(() => {
                this.show = false
            }, 3000);
        }
    }
};
</script>

<style>
.flash-message {
    position: fixed;
    right: 25px;
    bottom: 40px;
}
</style>

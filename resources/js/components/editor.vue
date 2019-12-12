<template>
<div>
    <input id="trix" type="hidden" :name="this.name" :value="this.value">

    <trix-editor ref="trix" input="trix" id="thread-reply"></trix-editor>
</div>
</template>

<script>
import Trix from 'trix';
export default {
    props: ['name', 'value'],
    mounted() {
        this.$refs.trix.addEventListener('trix-change', e => {
            this.$emit('TrixChanged', e.target.value);
        });
    }
}
</script>

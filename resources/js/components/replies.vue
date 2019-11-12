<template>
<div>
    <div v-for="(reply, index) in items">
        <reply :id="reply.id" :data="reply" :key="reply.id" :thread="reply.thread_id" @deleted="remove(index)"></reply>
    </div>
    <paginator :dataSet="dataSet" @changed="fetch"></paginator>
    <NewReply @ReplyCreated="add"></NewReply>
</div>
</template>

<script>
import reply from './reply.vue';
import NewReply from './NewReply.vue';
import collection from '../mixin/collection.vue';

export default {
    data() {
        return {
            items: [],
            dataSet: false,
        }
    },
    components: {
        reply,
        NewReply
    },
    mixins: [collection],
    created() {
        this.fetch();
    },
    methods: {

        fetch(page) {
            axios.get(this.getUrl(page)).then(this.refresh);
        },
        refresh(response) {
            this.items = response.data.data;
            this.dataSet = response.data;
            window.scrollTo(0, 0);
        },
        getUrl(page) {
            if (!page) {
                let query = location.search.match(/page=(\d+)/);
                page = query ? query[1] : 1;
            }
            return `${location.pathname}/replies?page=${page}`;
        }
    },

}
</script>

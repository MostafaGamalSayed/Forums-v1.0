<template>
<div class="mt-3">
    <nav aria-label="Page navigation example" v-if="shouldPaginate">
        <ul class="pagination pagination-primary text-center justify-content-end mr-2">
            <li class="page-item" v-show="prevPageUrl">
                <a class="page-item" href="#" @click.prevent="page--" tabindex="-1">&laquo; Previous</a>
            </li>
            <li class="page-item" v-show="nextPageUrl">
                <a class="page-item" href="#" @click.prevent="page++">Next &raquo;</a>
            </li>
        </ul>
    </nav>
</div>
</template>

<script>
export default {
    props: ['dataSet'],
    data() {
        return {
            page: 1,
            nextPageUrl: false,
            prevPageUrl: false,
        }
    },
    computed: {
        shouldPaginate() {
            return !!this.nextPageUrl || !!this.prevPageUrl;
        },
    },
    watch: {
        dataSet() {
            this.page = this.dataSet.current_page;
            this.nextPageUrl = this.dataSet.next_page_url;
            this.prevPageUrl = this.dataSet.prev_page_url;
        },
        page() {
            this.broadcast().updateUrl();
        },
    },
    methods: {
        broadcast() {
            return this.$emit('changed', this.page);
        },
        updateUrl() {
            history.pushState(null, null, '?page=' + this.page);
        },
    },
}
</script>

<template>
<ais-instant-search index-name="threads" :search-client="searchClient">
    <div class="left-panel">
        <ais-clear-refinements />
        <h2 class="heading mt-3 font-weight-bold">
            <i class="fa fa-filter"></i>
            Filter by channel
        </h2>
        <ais-refinement-list attribute="channel.name" searchable :class-names="{
          'ais-RefinementList-label': 'font-wright-bold ml-1 text-capitalize',
          'ais-RefinementList-list':'nav flex-column',
          'ais-RefinementList-item':'nav-item sidebarLink p-1',
          'ais-RefinementList-noResults': 'No results text-center text-danger'}" />
        <h2 class="heading mt-3 font-weight-bold">
            <i class="fa fa-filter"></i>
            Is Solved ?
        </h2>
        <ais-refinement-list attribute="isSolved" :class-names="{
            'ais-RefinementList-label': 'font-wright-bold ml-1 text-capitalize',
            'ais-RefinementList-list':'nav flex-column',
            'ais-RefinementList-item':'nav-item sidebarLink p-1',
            'ais-RefinementList-noResults': 'No results text-center text-danger'}" :transform-items="transform" />
        <ais-configure :hitsPerPage="8" :query="q" />
    </div>
    <div class="right-panel">
        <ais-search-box placeholder="Search For Threads" submit-title="GO" reset-title="reset" autofocus show-loading-indicator :class-names="{'ais-SearchBox-input': 'form-control form-control-alternative'}" />
        <ais-stats :class-names="{'ais-Stats-text': 'font-weight-bold font-italic ml-2', 'ais-Stats':'d-inline'}" />
        <br />
        <ais-current-refinements :class-names="{'ais-CurrentRefinements':'mb-4', 'ais-CurrentRefinements-item': 'bg-gradient-danger', 'ais-CurrentRefinements-label': 'font-weight-bold'}" />
        <ais-hits>
            <div slot-scope="{ items }">
                <ul v-for="item in items" class="list-unstyled">
                    <li class="media">
                        <img :src="root + item.owner.searchAvatar" style="height: 60px; width: 60px" class="mr-3 rounded-circle align-self-center" :alt="item.owner.name">
                        <div class="media-body mt-2">
                            <a :href="'/' + item.path" class="thread-title text-dark font-weight-bolder">
                                <ais-highlight attribute="title" :hit="item">
                                </ais-highlight>
                            </a>
                            <small class="d-block text-muted">
                                <a :href="'/profiles/' + item.owner.name" class="text-uppercase font-weight-bold">
                                    {{ item.owner.name }}
                                </a>
                                Posted
                                <span class="font-weight-bold">
                                    9 hours ago
                                </span>
                            </small>
                        </div>
                    </li>
                </ul>
            </div>
        </ais-hits>
        <ais-pagination></ais-pagination>
    </div>
</ais-instant-search>
</template>

<script>
import algoliasearch from 'algoliasearch/lite';
import 'instantsearch.css/themes/algolia.css';
export default {
    props: ['query'],
    data() {
        return {
            searchClient: algoliasearch(
                'EWD0GU8Z5K',
                '5418fbfad9e9eeb06fd96dab36e6ac9a'
            ),
            q: this.query,
            root: 'http://' + window.location.href.split('/')[2] + '/storage/'
        };
    },
    methods: {
        transform(items) {
            return items.map((item, index) => ({
                ...item,
                label: item.label === 'false' ? 'Un-solved' : 'Solved',
            }));
        }
    },

};
</script>

<style>
.ais-Hits-list {
    margin-top: 0;
    margin-bottom: 1em;
}

.ais-InstantSearch {
    display: grid;
    grid-template-columns: 1fr 4fr;
    grid-gap: 1em;
}

ul .sidebarLink:hover {
    background-color: #f6f6f6;
}
</style>

<template>
    <div>
        <button type="submit"  class="bg-transparent border-0 text-red" style="cursor: pointer" @click="toggle" data-toggle="tooltip" :title= "title" data-placement="top">
            <i :class="classes"></i>
        </button>
        <span v-text="favoritesCount"></span>
    </div>
</template>

<script>
    export default {
        props:[
            'reply'
        ],

        data(){
            return{
                favoritesCount: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited
            }
        },
        computed:{
            classes(){
                return [
                    'fa fa-heart' ,
                    this.isFavorited ? 'text-danger' : 'text-muted'
                ];
            },
            title(){
                return this.isFavorited ? 'un-favorite' : 'favorite';
            }
        },
        methods:{
            toggle(){
                this.isFavorited ?  this.unFavorite() : this.favorite();
            },
            favorite(){
                axios.post('/replies/' + this.reply.id + '/favorite');
                this.favoritesCount ++;
                this.isFavorited = true;
                flash('The reply has been favorite');

            },
            unFavorite(){
                axios.delete('/replies/' + this.reply.id + '/favorite');
                this.favoritesCount --;
                this.isFavorited = false;
                flash('The reply has been un-favorite');
            },
        },
    }
</script>
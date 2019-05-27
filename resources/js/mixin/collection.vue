<script>
    export default {
        data(){
            items: []
        },
        methods:{
            remove(index){
                this.items.splice(index, 1);
            },
            add(item){
                this.items.push(item);
            },
        },
    }
</script>
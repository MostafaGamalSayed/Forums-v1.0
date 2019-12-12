<script>
import replies from '../replies.vue';
import subscribe from '../subscription.vue';

export default {
    props: ['content'],
    data() {
        return {
            body: this.content
        }
    },
    components: {
        replies,
        subscribe
    }
}
</script>

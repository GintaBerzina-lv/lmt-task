import { Link } from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import PostReactions from '@/Pages/Post/Reactions/PostReactions.vue'

export default {
  name: 'PostList',
  components: {
    Link,
    PrimaryButton,
    PostReactions,
    Pagination
  },
  props: {
    posts: Object,
    reactions: Array
  },
  computed: {
    currentUser() {
      return this.$page.props.auth.user;
    }
  }
}

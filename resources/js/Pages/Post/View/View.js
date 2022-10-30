import { useForm } from '@inertiajs/inertia-vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import PostReactions from '@/Pages/Post/Reactions/PostReactions.vue'

export default {
  name: 'PostView',
  components: {
    InputError,
    InputLabel,
    PrimaryButton,
    DangerButton,
    TextInput,
    TextareaInput,
    PostReactions
  },
  props: {
    post: Object,
    statuses: Array,
    reactions: Array
  },
  data () {
    return {
      form: useForm(this.post)
    }
  },
  computed: {
    currentUser() {
      return this.$page.props.auth.user;
    }
  },
  methods: {
    save () {
      return this.form.post(route('posts.edit'));
    },
    deleteItem () {
      return this.form.delete(route('posts.delete', { id: this.post.id }));
    }
  }
}

import PrimaryButton from '@/Components/PrimaryButton.vue';

export default {
  name: 'PostReaction',
  components: {
    PrimaryButton
  },
  props: {
    post: Object,
    reactions: Array
  },
  computed: {
    currentUser() {
      return this.$page.props.auth.user;
    }
  },
  methods: {
    hasReaction (reactionId) {
      if (this.currentUser) {
        return (this.post.reactions.findIndex(el => (el.user_id == this.currentUser.id && el.reaction_id == reactionId)) >= 0);
      }
      return false;
    },
    toggleReaction (reaction) {
      if (this.currentUser) {
        this.$inertia.post(route('posts.react', { id: this.post.id }), { reaction_id: reaction.id, delete: this.hasReaction(reaction.id) });
      }
    }
  }
}

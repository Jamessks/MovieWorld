<template>
  <div class="flex items-center space-x-4 mb-4">
    <span class="text-green-600 font-semibold">Likes: {{ likes }}</span>
    <span class="text-red-600 font-semibold">Dislikes: {{ dislikes }}</span>
  </div>
  <form id="reaction_form">
    <div class="flex space-x-4">
      <button type="submit" @click="like" :class="likeClass" :disabled="loading" class="p-2 text-white rounded hover:bg-green-700 transition duration-200">
        <i class="fas fa-thumbs-up"></i> Like
      </button>
      <button type="submit" @click="dislike" :class="dislikeClass" :disabled="loading" class="p-2 text-white rounded hover:bg-red-700 transition duration-200">
        <i class="fas fa-thumbs-down"></i> Dislike
      </button>
    </div>
  </form>
</template>

<script>
export default {
  name: "LikeDislike",
  props: {
    movie: {
      type: [Number, String],
      required: true,
    },
    reaction: {
      type: [Number, String],
    },
    reference: {
      type: String,
      required: true,
    },
    likes: {
      type: [Number, String],
      required: true,
    },
    dislikes: {
      type: [Number, String],
      required: true,
    },
  },
  data() {
    return {
      userReaction: this.reaction ?? null,
      likes: this.likes,
      dislikes: this.dislikes,
      loading: false, // Flag to track if the request is in progress
    };
  },
  methods: {
    async like(event) {
      event.preventDefault();
      if (this.loading) return;

      const newReaction = this.userReaction === 1 ? null : 1;
      await this.updateReaction(newReaction);
    },
    async dislike(event) {
      event.preventDefault();
      if (this.loading) return;

      const newReaction = this.userReaction === 0 ? null : 0;
      await this.updateReaction(newReaction); // Pass the new reaction state
    },
    async updateReaction(newReaction) {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      
      // Indicate that the request is in progress
      this.loading = true;

      const response = await fetch(`/api/reaction`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': csrfToken,
        },
        body: JSON.stringify({
          movie: this.movie,
          reaction: newReaction,
          reference: this.reference,
        }),
      });

      this.loading = false;

      if (!response.ok) {
        throw new Error('Failed to update reaction');
      }

      const responseText = await response.json();

      let data = {};
      try {
        data = JSON.parse(responseText.data);
      } catch (e) {
        console.error("Failed to parse data:", e);
      }

      // Only update the userReaction and counts if the response was successful
      this.userReaction = newReaction;
      this.likes = data.likes;
      this.dislikes = data.dislikes;
    },
  },
  computed: {
    likeClass() {
      return this.userReaction === 1 ? "bg-green-700" : "bg-green-600";
    },
    dislikeClass() {
      return this.userReaction === 0 ? "bg-red-700" : "bg-red-600";
    },
  },
};
</script>

<style scoped>
</style>
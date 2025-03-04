<template>
    <div class="min-h-screen bg-gray-100 py-8">
        <div class="mx-auto max-w-5xl px-4">
            <h1 class="mb-6 text-2xl font-bold text-gray-800">Recent Posts</h1>
            <div class="mb-6">
                <input
                    v-model="searchQuery"
                    @input="handleSearch"
                    type="text"
                    placeholder="Search posts by title, content, or comments..."
                    class="w-full rounded border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                />
            </div>

            <div v-for="post in posts" :key="post.id" class="mb-8">
                <div class="mb-4 rounded-lg bg-white p-6 shadow-sm">
                    <div class="mb-2 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <img
                                class="h-10 w-10 rounded-full object-cover"
                                src="https://via.placeholder.com/40"
                                alt="Author Avatar"
                            />
                            <div>
                                <p class="font-semibold text-gray-700">
                                    {{ post.user ? post.user.name : 'Unknown' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ formatDate(post.created_at) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <h2 class="mb-2 text-xl font-bold text-gray-800">{{ post.title }}</h2>
                    <p class="mb-4 leading-relaxed text-gray-600">{{ post.content }}</p>

                    <div class="mt-2 flex items-center space-x-4">
                        <router-link
                            v-if="!post.comments || post.comments.length === 0"
                            :to="{ name: 'posts.edit', params: { id: post.id } }"
                            class="text-blue-500 hover:underline"
                        >
                            Edit
                        </router-link>

                        <button
                            v-if="!post.comments || post.comments.length === 0"
                            class="text-red-500 hover:underline"
                            @click="deletePost(post.id)"
                        >
                            Delete
                        </button>

                        <div v-else class="text-gray-500 text-sm italic">
                            Cannot edit/delete post after comments have been added.
                        </div>
                    </div>

                    <CommentsSection
                        :postId="post.id"
                        :initialComments="post.comments"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import usePosts from '@/composables/posts'
import CommentsSection from '@/components/Comments/CommentsSection.vue'


const {posts, getPosts, deletePost} = usePosts()

const searchQuery = ref('')

onMounted(() => {
    getPosts()
})

function handleSearch() {
    getPosts(searchQuery.value)
}

function formatDate(dateString) {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString()
}
</script>


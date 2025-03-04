<template>
    <form @submit.prevent="storePost(post)">
        <div>
            <label for="post-title" class="block text-sm font-medium text-gray-700">
                Title
            </label>
            <input v-model="post.title" id="post-title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <div class="text-red-600 mt-1">
                <div v-for="message in validationErrors?.title">
                    {{ message }}
                </div>
            </div>
        </div>

        <div class="mt-4">
            <label for="post-content" class="block text-sm font-medium text-gray-700">
                Content
            </label>
            <textarea v-model="post.content" id="post-content" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
            <div class="text-red-600 mt-1">
                <div v-for="message in validationErrors?.content">
                    {{ message }}
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button :disabled="isLoading" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm uppercase text-white disabled:opacity-75 disabled:cursor-not-allowed">
                <span v-show="isLoading" class="inline-block animate-spin w-4 h-4 mr-2 border-t-2 border-t-white border-r-2 border-r-white border-b-2 border-b-white border-l-2 border-l-blue-600 rounded-full"></span>
                <span v-if="isLoading">Processing...</span>
                <span v-else>Save</span>
            </button>
        </div>
    </form>
</template>


<script setup>
import { reactive } from 'vue'
import usePosts from '@/composables/posts';

const { storePost, validationErrors, isLoading } = usePosts()

const post = reactive({
    title: '',
    content: '',
})

</script>

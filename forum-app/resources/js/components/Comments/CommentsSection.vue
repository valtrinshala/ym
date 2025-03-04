<template>
    <div class="mt-4 rounded-lg bg-white p-6 shadow-sm">
        <div class="mb-4">
            <h3 class="mb-2 text-lg font-semibold text-gray-700">Discussion</h3>
            <div class="flex space-x-3">
                <img
                    class="h-10 w-10 rounded-full object-cover"
                    :src="userAvatar"
                    alt="Your Avatar"
                />
                <div class="flex-1">
          <textarea
              v-model="newComment.body"
              class="w-full rounded-lg border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
              rows="2"
              placeholder="Write a comment..."
          ></textarea>
                    <div class="mt-2 text-right">
                        <button
                            @click="submitComment"
                            class="rounded bg-blue-500 px-4 py-2 text-white transition hover:bg-blue-600"
                        >
                            Post
                        </button>
                    </div>
                    <div v-if="validationErrors.body" class="text-red-500 text-sm mt-1">
                        {{ validationErrors.body[0] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="space-y-4">
            <div
                v-for="comment in comments"
                :key="comment.id"
                class="rounded-lg bg-gray-50 p-4"
            >
                <div class="flex items-start space-x-3">
                    <img
                        class="h-10 w-10 rounded-full object-cover"
                        :src="comment.user?.avatar || 'https://via.placeholder.com/40'"
                        alt="Commenter Avatar"
                    />
                    <div class="flex-1">
                        <div class="mb-1 flex items-center justify-between">
                            <h4 class="font-semibold text-gray-700">
                                {{ comment.user ? comment.user.name : 'Guest' }}
                            </h4>
                            <span class="text-sm text-gray-500">
                {{ formatDate(comment.created_at) }}
                <span v-if="comment.updated_at !== comment.created_at">
                  (edited)
                </span>
              </span>
                        </div>
                        <div v-if="editCommentId === comment.id">
                            <!-- Edit Mode -->
                            <textarea
                                v-model="editCommentBody"
                                class="w-full rounded-lg border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                rows="2"
                            ></textarea>
                            <div class="mt-2 space-x-2">
                                <button
                                    @click="saveComment(comment.id)"
                                    class="rounded bg-blue-500 px-3 py-1 text-white transition hover:bg-blue-600"
                                >
                                    Save
                                </button>
                                <button
                                    @click="cancelEdit"
                                    class="rounded bg-gray-300 px-3 py-1 text-gray-800 transition hover:bg-gray-400"
                                >
                                    Cancel
                                </button>
                            </div>
                            <div v-if="validationErrors.body" class="text-red-500 text-sm mt-1">
                                {{ validationErrors.body[0] }}
                            </div>
                        </div>
                        <div v-else>
                            <p class="leading-relaxed text-gray-600 mb-1">{{ comment.body }}</p>
                            <div  class="flex items-center space-x-2 text-sm">
                                <button
                                    @click="startEditComment(comment.id, comment.body)"
                                    class="text-blue-500 hover:underline"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="deleteExistingComment(comment.id)"
                                    class="text-red-500 hover:underline"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import useComments from '@/composables/comments'

const props = defineProps({
    postId: {
        type: Number,
        required: true
    },
    initialComments: {
        type: Array,
        default: () => []
    }
})


const { comments, validationErrors, isLoading, getComments, storeComment, updateComment, deleteComment } = useComments()

comments.value = props.initialComments

// New comment form data
const newComment = reactive({
    body: ''
})

const editCommentId = ref(null)
const editCommentBody = ref('')

const userAvatar = 'https://via.placeholder.com/40'

function formatDate(dateString) {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString()
}

async function submitComment() {
    if (isLoading.value) return
    await storeComment(props.postId, newComment)
    if (!validationErrors.value.body) {
        newComment.body = ''
    }
}

function startEditComment(commentId, currentBody) {
    editCommentId.value = commentId
    editCommentBody.value = currentBody
}

function cancelEdit() {
    editCommentId.value = null
    editCommentBody.value = ''
}

async function saveComment(commentId) {
    await updateComment(props.postId, commentId, editCommentBody.value)
    if (!validationErrors.value.body) {
        cancelEdit()
    }
}

function deleteExistingComment(commentId) {
    deleteComment(props.postId, commentId)
}
</script>


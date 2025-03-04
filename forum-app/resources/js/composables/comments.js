import { ref, inject } from 'vue'
import axios from 'axios'

export default function useComments() {
    const comments = ref([])
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getComments = async (postId) => {
        try {
            const response = await axios.get(`/api/posts/${postId}/comments`)
            comments.value = response.data.data
        } catch (error) {
            console.error('Error fetching comments:', error)
        }
    }

    const storeComment = async (postId, commentData) => {
        try {
            isLoading.value = true
            validationErrors.value = {}
            const response = await axios.post(`/api/posts/${postId}/comments`, commentData)
            comments.value.unshift(response.data.data)
        } catch (error) {
            if (error.response?.data?.errors) {
                validationErrors.value = error.response.data.errors
            }
            console.error('Error storing comment:', error)
        } finally {
            isLoading.value = false
        }
    }

    const updateComment = async (postId, commentId, newBody) => {
        try {
            isLoading.value = true
            validationErrors.value = {}
            const response = await axios.put(`/api/posts/${postId}/comments/${commentId}`, { body: newBody })
            const index = comments.value.findIndex(c => c.id === commentId)
            if (index !== -1) {
                comments.value[index] = response.data.data
            }
        } catch (error) {
            if (error.response?.data?.errors) {
                validationErrors.value = error.response.data.errors
            }
            console.error('Error updating comment:', error)
        } finally {
            isLoading.value = false
        }
    }

    const deleteComment = async (postId, commentId) => {
        swal({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this action!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonColor: '#ef4444',
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) {
                axios.delete(`/api/posts/${postId}/comments/${commentId}`)
                    .then(response => {
                        comments.value = comments.value.filter(c => c.id !== commentId)
                        swal({
                            icon: 'success',
                            title: 'Comment deleted successfully'
                        })
                    })
                    .catch(error => {
                        swal({
                            icon: 'error',
                            title: 'Something went wrong'
                        })
                    })
            }
        })
    }

    return {
        comments,
        validationErrors,
        isLoading,
        getComments,
        storeComment,
        updateComment,
        deleteComment
    }
}

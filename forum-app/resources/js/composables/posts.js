import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default function usePosts() {
    const posts = ref([])
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const post = ref({})
    const swal = inject('$swal')

    const getPost = async (id) => {
        axios.get('/api/posts/' + id)
            .then(response => {
                post.value = response.data.data;
            })
    }

    const getPosts = async (search = '') => {
        axios.get('/api/posts', { params: { q: search } })
            .then(response => {
                posts.value = response.data.data;
            })
            .catch(error => {
                console.error('Error fetching posts:', error);
            })
    }

    const storePost = async (postData) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        axios.post('/api/posts', postData)
            .then(response => {
                router.push({ name: 'posts.index' })
                swal({
                    icon: 'success',
                    title: 'Post saved successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
                isLoading.value = false
            })
    }

    const updatePost = async (postData) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        axios.put('/api/posts/' + postData.id, postData)
            .then(response => {
                router.push({ name: 'posts.index' })
                swal({
                    icon: 'success',
                    title: 'Post saved successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const deletePost = async (id) => {
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
        })
            .then(result => {
                if (result.isConfirmed) {
                    axios.delete('/api/posts/' + id)
                        .then(response => {
                            getPosts()
                            router.push({ name: 'posts.index' })
                            swal({
                                icon: 'success',
                                title: 'Post deleted successfully'
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
        posts,
        post,
        getPosts,
        getPost,
        storePost,
        updatePost,
        deletePost,
        validationErrors,
        isLoading
    }
}

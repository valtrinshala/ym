import {ref} from 'vue'
import {useRouter} from 'vue-router'


export default function usePosts() {
    const posts = ref([])
    const router = useRouter()
    const validationErrors = ref({})

    const getPosts = async () => {
        axios.get('/api/posts')
            .then(response => {
                posts.value = response.data.data;
            })
    }

    const storePost = async (post) => {
        axios.post('/api/posts', post)
            .then(response => {
                router.push({name: 'posts.index'})
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
    }

    return { posts, getPosts, storePost, validationErrors }
}

import { ref, inject } from 'vue'
import axios from 'axios'

export default function useVerification() {
    const swal = inject('$swal')
    const sending = ref(false)
    const error = ref(null)

    async function sendVerificationEmail() {
        sending.value = true
        error.value = null

        try {
            const response = await axios.post('/api/email/verify/send')
            swal({
                icon: 'success',
                title: response.data.message || 'Verification email sent! Please check your inbox.'
            })
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to send verification email.'
            swal({
                icon: 'error',
                title: 'Error',
                text: error.value
            })
        } finally {
            sending.value = false
        }
    }

    return {
        sending,
        error,
        sendVerificationEmail
    }
}

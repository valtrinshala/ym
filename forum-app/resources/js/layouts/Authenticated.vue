<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <router-link to="/" class="block">
                                <svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg" class="block h-9 w-auto fill-current text-gray-800">
                                </svg>
                            </router-link>
                        </div>
                        <div class="hidden sm:flex sm:space-x-8 sm:ml-10">
                            <router-link
                                :to="{ name: 'posts.index' }"
                                active-class="border-b-2 border-indigo-400"
                                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 hover:text-indigo-500"
                            >
                                Posts
                            </router-link>
                            <router-link
                                v-if="user.email_verified_at"
                                :to="{ name: 'posts.create' }"
                                active-class="border-b-2 border-indigo-400"
                                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 hover:text-indigo-500"
                            >
                                Create Post
                            </router-link>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button @click="toggleDropdown" class="flex items-center focus:outline-none">
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900">Hi, {{ user.name }}</div>
                                    <div class="text-xs text-gray-500">{{ user.email }}</div>
                                </div>
                                <svg class="ml-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <!-- Dropdown Menu -->
                            <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded shadow-lg z-20">
                                <button
                                    @click="logout"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    Log out
                                </button>
                            </div>
                        </div>

                        <div v-if="!user.email_verified_at">
                            <button
                                @click="openVerifyModal"
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200 focus:outline-none"
                                :disabled="sending"
                            >
                                <span v-if="!sending">Unverified</span>
                                <span v-else>Sending...</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </nav>

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ pageTitle }}
                </h2>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <router-view></router-view>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Verification Modal -->
        <div v-if="verifyModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-30">
            <div class="bg-white rounded-lg shadow p-4 w-80">
                <h3 class="text-lg font-semibold mb-4">Verify Your Email</h3>
                <p class="mb-4 text-sm text-gray-700">
                    We will send you an email for verification. Please check your inbox after clicking the button below.
                </p>
                <div class="flex justify-end space-x-4">
                    <button @click="closeVerifyModal" class="px-3 py-2 bg-gray-300 rounded text-gray-800 hover:bg-gray-400">
                        Cancel
                    </button>
                    <button @click="handleVerification" class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Send Email
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue'
import {useRouter} from 'vue-router'
import useAuth from '@/composables/auth'
import useVerification from '@/composables/useVerification'

const router = useRouter()
const {user, processing, logout} = useAuth()
const {sending, sendVerificationEmail} = useVerification()

const pageTitle = ref('Forum')

const dropdownOpen = ref(false)

function toggleDropdown() {
    dropdownOpen.value = !dropdownOpen.value
}

const verifyModalOpen = ref(false)

function openVerifyModal() {
    verifyModalOpen.value = true
}

function closeVerifyModal() {
    verifyModalOpen.value = false
}

async function handleVerification() {
    await sendVerificationEmail()
    verifyModalOpen.value = false
}
</script>


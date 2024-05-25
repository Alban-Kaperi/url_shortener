<template>
    <div class="flex flex-col w-[500px]">
        <h3 class="text-3xl text-gray-600">Short URL</h3>
        <div class="relative sm:mr-4 mr-2 mt-4 border p-4 shadow">
            <span class="leading-7 text-sm text-gray-600"
                >Paste the URL to be shortened</span
            >
            <div>
                <form @submit.prevent="shortenUrl" class="flex shadow">
                    <input
                        v-model="url"
                        type="text"
                        id="footer-field"
                        name="footer-field"
                        class="w-full bg-gray-100 bg-opacity-50 rounded-l border border-gray-300 focus:ring-2 focus:bg-transparent focus:ring-indigo-200 focus:border-indigo-500 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                    />

                    <button
                        class="bg-indigo-500 px-2 rounded-r py-1 text-gray-50"
                    >
                        Shorten Url
                    </button>
                </form>
            </div>
        </div>

        <div
            v-if="shortUrl"
            class="relative sm:mr-4 mr-2 mt-4 border p-4 shadow"
        >
            <span class="leading-7 text-sm text-gray-600">Shortened URL</span>
            <div
                class="flex justify-between items-center p-4 w-full bg-gray-100 bg-opacity-50 rounded border border-gray-100"
            >
                <a :href="shortUrl" target="_blank" class="text-blue-500">{{
                    shortUrl
                }}</a>

                <button
                    @click="copyToClipboard"
                    class="bg-indigo-500 px-2 rounded py-2 text-gray-50"
                >
                    Copy URL
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, watch } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";

export default {
    setup() {
        const url = ref("");
        const shortUrl = ref("");
        const toast = useToast();

        const shortenUrl = async () => {
            try {
                const response = await axios.post(
                    "/api/shorten-url",
                    { url: url.value },
                    {
                        headers: {
                            "Content-Type": "application/json",
                        },
                    }
                );
                shortUrl.value = response.data.short_url;
            } catch (error) {
                if (error.response && error.response.status === 400) {
                    shortUrl.value = "";
                    toast.error(
                        "URL is not safe to browse. Please try another URL"
                    );
                } else {
                    toast.error("There was an error shortening the URL.");
                }
            }
        };

        const copyToClipboard = () => {
            navigator.clipboard
                .writeText(shortUrl.value)
                .then(() => {
                    toast.success("URL copied to clipboard");
                })
                .catch(() => {
                    toast.error("Failed to copy URL.");
                });
        };

        return { url, shortUrl, shortenUrl, copyToClipboard };
    },
};
</script>

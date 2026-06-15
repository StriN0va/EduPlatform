<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-[#0F172A] transition duration-150 ease-in-out hover:bg-[#F8FAFC] focus:bg-[#F8FAFC] focus:outline-none"
    >
        <slot />
    </Link>
</template>

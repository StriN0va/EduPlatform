<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#0D9488] text-start text-base font-medium text-[#0F172A] bg-[#F8FAFC] focus:outline-none focus:text-[#0F172A] focus:bg-[#F8FAFC] focus:border-[#0F172A] transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[#1E3A8A] hover:text-[#0F172A] hover:bg-[#FFFFFF] hover:border-[#0D9488] focus:outline-none focus:text-[#0F172A] focus:bg-[#FFFFFF] focus:border-[#0D9488] transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

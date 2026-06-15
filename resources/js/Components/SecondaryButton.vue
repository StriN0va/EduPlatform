<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-md border border-[#0D9488] bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-[#0F172A] shadow-sm transition duration-150 ease-in-out hover:bg-[#FFFFFF] focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:ring-offset-2 disabled:opacity-25"
    >
        <slot />
    </button>
</template>

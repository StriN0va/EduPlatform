<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Новый пароль" />

    <div class="min-h-screen bg-[#F8FAFC] px-4 py-10 flex items-center justify-center">
        <div class="w-full max-w-md rounded-[28px] border border-[#F8FAFC] bg-white p-7 shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
            <Link :href="route('login')" class="inline-flex items-center gap-2 text-sm font-medium text-[#1E3A8A] transition hover:text-[#0F172A]">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#F8FAFC] text-[#1E3A8A]">&larr;</span>
                Назад ко входу
            </Link>

            <h1 class="mt-6 text-3xl font-bold text-[#0F172A]">Новый пароль</h1>
            <p class="mt-3 text-sm leading-7 text-[#1E3A8A]">
                Укажите новый пароль для своего аккаунта.
            </p>

            <form @submit.prevent="submit" class="mt-6 space-y-4">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-[#0F172A]">Электронная почта</label>
                    <input v-model="form.email" type="email" required autocomplete="username" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                    <InputError class="mt-2 text-sm text-[#EF4444]" :message="form.errors.email" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-[#0F172A]">Пароль</label>
                    <input v-model="form.password" type="password" required autocomplete="new-password" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                    <InputError class="mt-2 text-sm text-[#EF4444]" :message="form.errors.password" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-[#0F172A]">Подтверждение пароля</label>
                    <input v-model="form.password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                    <InputError class="mt-2 text-sm text-[#EF4444]" :message="form.errors.password_confirmation" />
                </div>

                <button type="submit" :disabled="form.processing" class="inline-flex w-full items-center justify-center rounded-2xl bg-[#F59E0B] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 disabled:translate-y-0 disabled:opacity-50">
                    Сохранить пароль
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: { type: Boolean },
    status: { type: String },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Войти" />

    <div class="min-h-screen bg-[#F8FAFC] px-4 py-10">
        <div class="mx-auto grid min-h-[calc(100vh-5rem)] max-w-6xl items-center gap-8 lg:grid-cols-[minmax(0,1.05fr)_460px]">
            <div class="hidden lg:block">
                <div class="rounded-[34px] bg-white p-8 shadow-[0_30px_70px_-40px_rgba(15,23,42,0.45)]">
                    <Link :href="route('home')" class="inline-flex items-center gap-2 text-sm font-medium text-[#1E3A8A] transition hover:text-[#0F172A]">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-[#1E3A8A] shadow-sm">&larr;</span>
                        На главную
                    </Link>
                    <div class="mt-8 flex flex-wrap gap-2">
                        <span class="rounded-full bg-[#1E3A8A] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                            EduPlatform
                        </span>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-[#1E3A8A] ring-1 ring-[#F8FAFC]">
                            Вход в систему
                        </span>
                    </div>
                    <h1 class="mt-6 text-5xl font-bold leading-tight text-[#0F172A]">
                        Возвращайтесь к обучению без лишних шагов.
                    </h1>
                    <p class="mt-5 max-w-xl text-lg leading-8 text-[#1E3A8A]">
                        Авторизуйтесь, чтобы продолжить обучение, следить за прогрессом и управлять своими курсами в одном кабинете.
                    </p>
                </div>
            </div>

            <div class="w-full">
                <div class="overflow-hidden rounded-[30px] border border-[#F8FAFC] bg-white shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
                    <div class="border-b border-[#F8FAFC] bg-white px-6 py-8">
                        <Link :href="route('home')" class="inline-flex items-center gap-2 text-sm font-medium text-[#1E3A8A] transition hover:text-[#0F172A] lg:hidden">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#F8FAFC] text-[#1E3A8A]">&larr;</span>
                            Назад
                        </Link>
                        <h2 class="mt-4 text-3xl font-bold text-[#0F172A] lg:mt-0">Вход в профиль</h2>
                        <p class="mt-3 text-base leading-7 text-[#1E3A8A]">
                            Введите почту и пароль, чтобы продолжить обучение.
                        </p>
                    </div>

                    <div class="px-6 py-8">
                        <div v-if="status" class="mb-5 rounded-2xl bg-[#ecfdf5] px-4 py-3 text-sm font-medium text-[#0D9488] ring-1 ring-[#ccfbf1]">
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-[#0F172A]">Электронная почта</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]"
                                    placeholder="name@example.com"
                                />
                                <InputError class="mt-2 text-sm text-[#EF4444]" :message="form.errors.email" />
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-[#0F172A]">Пароль</label>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]"
                                    placeholder="Введите пароль"
                                />
                                <InputError class="mt-2 text-sm text-[#EF4444]" :message="form.errors.password" />
                            </div>

                            <div class="flex justify-end">
                                <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-medium text-[#1E3A8A] transition hover:text-[#0F172A]">
                                    Не помню пароль
                                </Link>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-[#F59E0B] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.95)] disabled:translate-y-0 disabled:opacity-50"
                            >
                                Войти
                            </button>

                            <Link :href="route('register')" class="inline-flex w-full items-center justify-center rounded-2xl border border-[#F8FAFC] bg-white px-6 py-3 text-sm font-medium text-[#0F172A] transition hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A]">
                                Создать профиль
                            </Link>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

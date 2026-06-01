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

    <div class="min-h-screen bg-slate-100 px-4 py-10">
        <div class="mx-auto grid min-h-[calc(100vh-5rem)] max-w-6xl items-center gap-8 lg:grid-cols-[minmax(0,1.05fr)_460px]">
            <div class="hidden lg:block">
                <div class="rounded-[34px] bg-[linear-gradient(145deg,#eef2ff_0%,#ffffff_52%,#e2e8f0_100%)] p-8 shadow-[0_30px_70px_-40px_rgba(15,23,42,0.45)]">
                    <Link :href="route('home')" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 transition hover:text-indigo-700">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-indigo-600 shadow-sm">&larr;</span>
                        На главную
                    </Link>
                    <div class="mt-8 flex flex-wrap gap-2">
                        <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                            EduPlatform
                        </span>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-slate-200">
                            Вход в систему
                        </span>
                    </div>
                    <h1 class="mt-6 text-5xl font-bold leading-tight text-slate-950">
                        Возвращайтесь к обучению без лишних шагов.
                    </h1>
                    <p class="mt-5 max-w-xl text-lg leading-8 text-slate-600">
                        Авторизуйтесь, чтобы продолжить обучение, следить за прогрессом и управлять своими курсами в одном кабинете.
                    </p>
                </div>
            </div>

            <div class="w-full">
                <div class="overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
                    <div class="border-b border-slate-200 bg-[linear-gradient(135deg,#eef2ff_0%,#ffffff_58%,#f8fafc_100%)] px-6 py-8">
                        <Link :href="route('home')" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 transition hover:text-indigo-700 lg:hidden">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">&larr;</span>
                            Назад
                        </Link>
                        <h2 class="mt-4 text-3xl font-bold text-slate-950 lg:mt-0">Вход в профиль</h2>
                        <p class="mt-3 text-base leading-7 text-slate-600">
                            Введите почту и пароль, чтобы продолжить обучение.
                        </p>
                    </div>

                    <div class="px-6 py-8">
                        <div v-if="status" class="mb-5 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 ring-1 ring-emerald-100">
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Электронная почта</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                                    placeholder="name@example.com"
                                />
                                <InputError class="mt-2 text-sm text-red-500" :message="form.errors.email" />
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Пароль</label>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                                    placeholder="Введите пароль"
                                />
                                <InputError class="mt-2 text-sm text-red-500" :message="form.errors.password" />
                            </div>

                            <div class="flex justify-end">
                                <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-700">
                                    Не помню пароль
                                </Link>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.95)] disabled:translate-y-0 disabled:opacity-50"
                            >
                                Войти
                            </button>

                            <Link :href="route('register')" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-medium text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                Создать профиль
                            </Link>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

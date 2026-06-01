<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const { auth } = usePage().props;
const user = auth.user;

const profileForm = useForm({
    name: user.name,
    username: user.username ?? '',
    email: user.email,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const deleteForm = useForm({
    password: '',
});

const updateProfile = () => profileForm.patch(route('profile.update'));

const updatePassword = () => passwordForm.put(route('password.update'), {
    onSuccess: () => passwordForm.reset(),
});

const deleteAccount = () => {
    if (confirm('Удалить аккаунт? Это действие нельзя отменить.')) {
        deleteForm.delete(route('profile.destroy'));
    }
};
</script>

<template>
    <div class="min-h-screen bg-slate-100 flex flex-col">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-indigo-600">EduPlatform</Link>
                <div class="flex gap-4 items-center">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Курсы</Link>
                    <Link :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Кабинет</Link>
                    <Link :href="route('logout')" method="post" as="button" class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#64748b_0%,#475569_100%)] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(71,85,105,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_-18px_rgba(71,85,105,1)]">Выйти</Link>
                </div>
            </div>
        </nav>

        <div class="flex-1 max-w-6xl mx-auto w-full px-4 py-10">
            <section class="overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
                <div class="border-b border-slate-200 bg-[linear-gradient(135deg,#eef2ff_0%,#ffffff_58%,#f8fafc_100%)] px-6 py-8 lg:px-10">
                    <Link :href="route('dashboard')" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 transition hover:text-indigo-700">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">&larr;</span>
                        Назад в кабинет
                    </Link>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                            Профиль
                        </span>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-slate-200">
                            Управление аккаунтом
                        </span>
                    </div>
                    <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950">Настройки профиля</h1>
                    <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                        Обновляйте данные аккаунта, меняйте пароль и управляйте безопасностью учётной записи.
                    </p>
                </div>

                <div class="grid gap-6 px-6 py-8 lg:grid-cols-[minmax(0,1fr)_320px] lg:px-10">
                    <div class="space-y-6">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                            <h2 class="text-lg font-semibold text-slate-900">Личные данные</h2>
                            <p class="mt-2 text-sm leading-7 text-slate-500">Обновите имя, никнейм и электронную почту.</p>

                            <div v-if="status === 'profile-updated'" class="mt-4 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 ring-1 ring-emerald-100">
                                Профиль обновлён.
                            </div>

                            <form @submit.prevent="updateProfile" class="mt-5 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Полное имя</label>
                                    <input v-model="profileForm.name" type="text" required
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                    <p v-if="profileForm.errors.name" class="mt-2 text-sm text-red-500">{{ profileForm.errors.name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Никнейм</label>
                                    <input v-model="profileForm.username" type="text" required
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                    <p v-if="profileForm.errors.username" class="mt-2 text-sm text-red-500">{{ profileForm.errors.username }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Электронная почта</label>
                                    <input v-model="profileForm.email" type="email" required
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                    <p v-if="profileForm.errors.email" class="mt-2 text-sm text-red-500">{{ profileForm.errors.email }}</p>
                                </div>
                                <button type="submit" :disabled="profileForm.processing"
                                    class="inline-flex items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.95)] disabled:translate-y-0 disabled:opacity-50">
                                    Сохранить
                                </button>
                            </form>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                            <h2 class="text-lg font-semibold text-slate-900">Смена пароля</h2>
                            <p class="mt-2 text-sm leading-7 text-slate-500">Используйте надёжный пароль и не повторяйте старые комбинации.</p>

                            <div v-if="status === 'password-updated'" class="mt-4 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 ring-1 ring-emerald-100">
                                Пароль обновлён.
                            </div>

                            <form @submit.prevent="updatePassword" class="mt-5 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Текущий пароль</label>
                                    <input v-model="passwordForm.current_password" type="password" required
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                    <p v-if="passwordForm.errors.current_password" class="mt-2 text-sm text-red-500">{{ passwordForm.errors.current_password }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Новый пароль</label>
                                    <input v-model="passwordForm.password" type="password" required
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                    <p v-if="passwordForm.errors.password" class="mt-2 text-sm text-red-500">{{ passwordForm.errors.password }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Подтвердите пароль</label>
                                    <input v-model="passwordForm.password_confirmation" type="password" required
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                </div>
                                <button type="submit" :disabled="passwordForm.processing"
                                    class="inline-flex items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.95)] disabled:translate-y-0 disabled:opacity-50">
                                    Обновить пароль
                                </button>
                            </form>
                        </div>
                    </div>

                    <aside class="space-y-6">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Аккаунт</p>
                            <div class="mt-4 rounded-3xl border border-slate-200 bg-white p-5">
                                <p class="text-sm font-medium text-slate-500">Текущий пользователь</p>
                                <p class="mt-2 text-xl font-semibold text-slate-900">{{ user.name }}</p>
                                <p class="mt-1 text-sm text-slate-500">@{{ user.username }}</p>
                                <p class="mt-4 text-sm text-slate-500">{{ user.email }}</p>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-red-100 bg-white p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-red-400">Опасная зона</p>
                            <h2 class="mt-2 text-lg font-semibold text-slate-900">Удаление аккаунта</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-500">После удаления аккаунта восстановить его будет невозможно.</p>

                            <div class="mt-5">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Введите пароль для подтверждения</label>
                                <input v-model="deleteForm.password" type="password"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-red-400 focus:outline-none focus:ring-4 focus:ring-red-100" />
                                <p v-if="deleteForm.errors.password" class="mt-2 text-sm text-red-500">{{ deleteForm.errors.password }}</p>
                                <button @click="deleteAccount" :disabled="deleteForm.processing || !deleteForm.password"
                                    class="mt-4 inline-flex w-full items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#dc2626_0%,#ef4444_100%)] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(220,38,38,0.75)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(220,38,38,0.9)] disabled:translate-y-0 disabled:opacity-50">
                                    Удалить аккаунт
                                </button>
                            </div>
                        </div>
                    </aside>
                </div>
            </section>
        </div>

        <footer class="bg-gray-900 py-6 text-center text-gray-400 text-sm mt-10">
            EduPlatform 2026
        </footer>
    </div>
</template>

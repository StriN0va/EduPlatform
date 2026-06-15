<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import AppFooter from '@/Components/AppFooter.vue';

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
    <div class="min-h-screen bg-[#F8FAFC] flex flex-col">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-[#1E3A8A]">EduPlatform</Link>
                <div class="flex gap-4 items-center">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Курсы</Link>
                    <Link :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Кабинет</Link>
                    <Link :href="route('logout')" method="post" as="button" class="inline-flex items-center justify-center rounded-full bg-[#1E3A8A] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(15,23,42,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_-18px_rgba(15,23,42,1)]">Выйти</Link>
                </div>
            </div>
        </nav>

        <div class="flex-1 max-w-6xl mx-auto w-full px-4 py-10">
            <section class="overflow-hidden rounded-[30px] border border-[#F8FAFC] bg-white shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
                <div class="border-b border-[#F8FAFC] bg-white px-6 py-8 lg:px-10">
                    <Link :href="route('dashboard')" class="inline-flex items-center gap-2 text-sm font-medium text-[#1E3A8A] transition hover:text-[#0F172A]">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#F8FAFC] text-[#1E3A8A]">&larr;</span>
                        Назад в кабинет
                    </Link>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-[#1E3A8A] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                            Профиль
                        </span>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-[#1E3A8A] ring-1 ring-[#F8FAFC]">
                            Управление аккаунтом
                        </span>
                    </div>
                    <h1 class="mt-5 text-4xl font-bold tracking-tight text-[#0F172A]">Настройки профиля</h1>
                    <p class="mt-3 max-w-2xl text-base leading-7 text-[#1E3A8A]">
                        Обновляйте данные аккаунта, меняйте пароль и управляйте безопасностью учётной записи.
                    </p>
                </div>

                <div class="grid gap-6 px-6 py-8 lg:grid-cols-[minmax(0,1fr)_320px] lg:px-10">
                    <div class="space-y-6">
                        <div class="rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-6">
                            <h2 class="text-lg font-semibold text-[#0F172A]">Личные данные</h2>
                            <p class="mt-2 text-sm leading-7 text-[#1E3A8A]">Обновите имя, никнейм и электронную почту.</p>

                            <div v-if="status === 'profile-updated'" class="mt-4 rounded-2xl bg-[#ecfdf5] px-4 py-3 text-sm font-medium text-[#0D9488] ring-1 ring-[#ccfbf1]">
                                Профиль обновлён.
                            </div>

                            <form @submit.prevent="updateProfile" class="mt-5 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-[#0F172A] mb-2">Полное имя</label>
                                    <input v-model="profileForm.name" type="text" required
                                        class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                    <p v-if="profileForm.errors.name" class="mt-2 text-sm text-[#EF4444]">{{ profileForm.errors.name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-[#0F172A] mb-2">Никнейм</label>
                                    <input v-model="profileForm.username" type="text" required
                                        class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                    <p v-if="profileForm.errors.username" class="mt-2 text-sm text-[#EF4444]">{{ profileForm.errors.username }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-[#0F172A] mb-2">Электронная почта</label>
                                    <input v-model="profileForm.email" type="email" required
                                        class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                    <p v-if="profileForm.errors.email" class="mt-2 text-sm text-[#EF4444]">{{ profileForm.errors.email }}</p>
                                </div>
                                <button type="submit" :disabled="profileForm.processing"
                                    class="inline-flex items-center justify-center rounded-2xl bg-[#F59E0B] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.95)] disabled:translate-y-0 disabled:opacity-50">
                                    Сохранить
                                </button>
                            </form>
                        </div>

                        <div class="rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-6">
                            <h2 class="text-lg font-semibold text-[#0F172A]">Смена пароля</h2>
                            <p class="mt-2 text-sm leading-7 text-[#1E3A8A]">Используйте надёжный пароль и не повторяйте старые комбинации.</p>

                            <div v-if="status === 'password-updated'" class="mt-4 rounded-2xl bg-[#ecfdf5] px-4 py-3 text-sm font-medium text-[#0D9488] ring-1 ring-[#ccfbf1]">
                                Пароль обновлён.
                            </div>

                            <form @submit.prevent="updatePassword" class="mt-5 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-[#0F172A] mb-2">Текущий пароль</label>
                                    <input v-model="passwordForm.current_password" type="password" required
                                        class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                    <p v-if="passwordForm.errors.current_password" class="mt-2 text-sm text-[#EF4444]">{{ passwordForm.errors.current_password }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-[#0F172A] mb-2">Новый пароль</label>
                                    <input v-model="passwordForm.password" type="password" required
                                        class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                    <p v-if="passwordForm.errors.password" class="mt-2 text-sm text-[#EF4444]">{{ passwordForm.errors.password }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-[#0F172A] mb-2">Подтвердите пароль</label>
                                    <input v-model="passwordForm.password_confirmation" type="password" required
                                        class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                </div>
                                <button type="submit" :disabled="passwordForm.processing"
                                    class="inline-flex items-center justify-center rounded-2xl bg-[#F59E0B] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.95)] disabled:translate-y-0 disabled:opacity-50">
                                    Обновить пароль
                                </button>
                            </form>
                        </div>
                    </div>

                    <aside class="space-y-6">
                        <div class="rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Аккаунт</p>
                            <div class="mt-4 rounded-3xl border border-[#F8FAFC] bg-white p-5">
                                <p class="text-sm font-medium text-[#1E3A8A]">Текущий пользователь</p>
                                <p class="mt-2 text-xl font-semibold text-[#0F172A]">{{ user.name }}</p>
                                <p class="mt-1 text-sm text-[#1E3A8A]">@{{ user.username }}</p>
                                <p class="mt-4 text-sm text-[#1E3A8A]">{{ user.email }}</p>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-red-100 bg-white p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#EF4444]">Опасная зона</p>
                            <h2 class="mt-2 text-lg font-semibold text-[#0F172A]">Удаление аккаунта</h2>
                            <p class="mt-3 text-sm leading-7 text-[#1E3A8A]">После удаления аккаунта восстановить его будет невозможно.</p>

                            <div class="mt-5">
                                <label class="block text-sm font-semibold text-[#0F172A] mb-2">Введите пароль для подтверждения</label>
                                <input v-model="deleteForm.password" type="password"
                                    class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-red-400 focus:outline-none focus:ring-4 focus:ring-[#fee2e2]" />
                                <p v-if="deleteForm.errors.password" class="mt-2 text-sm text-[#EF4444]">{{ deleteForm.errors.password }}</p>
                                <button @click="deleteAccount" :disabled="deleteForm.processing || !deleteForm.password"
                                    class="mt-4 inline-flex w-full items-center justify-center rounded-2xl bg-[#EF4444] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(220,38,38,0.75)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(220,38,38,0.9)] disabled:translate-y-0 disabled:opacity-50">
                                    Удалить аккаунт
                                </button>
                            </div>
                        </div>
                    </aside>
                </div>
            </section>
        </div>

        <AppFooter class="mt-10" />
    </div>
</template>

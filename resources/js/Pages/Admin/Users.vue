<script setup>
import AppFooter from '@/Components/AppFooter.vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    users: Array,
    filters: Object,
});

const { auth } = usePage().props;
const search = ref(props.filters?.q ?? '');

const roleLabels = {
    student: 'Студент',
    teacher: 'Преподаватель',
    admin: 'Администратор',
};

const roleColors = {
    student: 'bg-blue-100 text-blue-700',
    teacher: 'bg-purple-100 text-purple-700',
    admin: 'bg-red-100 text-[#EF4444]',
};

const submitSearch = () => {
    router.get(route('admin.users'), { q: search.value }, {
        preserveState: true,
        replace: true,
    });
};

const resetSearch = () => {
    search.value = '';
    router.get(route('admin.users'), {}, {
        preserveState: true,
        replace: true,
    });
};

const changeRole = (user, newRole) => {
    if (newRole === user.role) return;

    const warn = user.role !== newRole ? `Изменить роль "${user.name}" на "${roleLabels[newRole]}"?` : '';
    if (confirm(warn)) {
        useForm({ role: newRole }).patch(route('admin.users.role', user.id));
    }
};

const deleteUser = (user) => {
    if (confirm(`Удалить аккаунт "${user.name}"?`)) {
        useForm({}).delete(route('admin.users.destroy', user.id));
    }
};
</script>

<template>
    <div class="min-h-screen bg-[#FFFFFF] flex flex-col">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-[#1E3A8A]">Дополнительное Образование</Link>
                <div class="flex gap-4 items-center">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Курсы</Link>
                    <Link :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Кабинет</Link>
                    <Link :href="route('admin.users')" class="inline-flex items-center justify-center rounded-full bg-[#F59E0B] px-4 py-2 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(30,58,138,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(30,58,138,1)]">Пользователи</Link>
                    <Link :href="route('logout')" method="post" as="button" class="inline-flex items-center justify-center rounded-full bg-[#1E3A8A] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(15,23,42,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_-18px_rgba(15,23,42,1)]">Выйти</Link>
                </div>
            </div>
        </nav>

        <div class="flex-1 max-w-6xl mx-auto w-full px-4 py-10">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Администрирование</p>
                    <h1 class="mt-2 text-3xl font-bold text-[#0F172A]">Управление пользователями</h1>
                </div>
                <span class="text-sm text-[#64748B]">Всего: {{ users.length }}</span>
            </div>

            <form @submit.prevent="submitSearch" class="mb-6 flex gap-3 rounded-[28px] border border-[#F8FAFC] bg-white p-4 shadow-sm">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Поиск по @логину или email"
                    class="min-w-0 flex-1 rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]"
                />
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[#F59E0B] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5">
                    Найти
                </button>
                <button type="button" @click="resetSearch" class="inline-flex items-center justify-center rounded-2xl border border-[#F8FAFC] bg-white px-5 py-3 text-sm font-medium text-[#0F172A] transition hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A]">
                    Сброс
                </button>
            </form>

            <div class="overflow-hidden rounded-[28px] border border-[#F8FAFC] bg-white shadow-[0_20px_40px_-30px_rgba(15,23,42,0.35)]">
                <table class="w-full text-sm">
                    <thead class="bg-[#FFFFFF] border-b border-[#F8FAFC]">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-[#1E3A8A]">Пользователь</th>
                            <th class="text-left px-6 py-3 font-medium text-[#1E3A8A]">Email</th>
                            <th class="text-left px-6 py-3 font-medium text-[#1E3A8A]">Роль</th>
                            <th class="text-left px-6 py-3 font-medium text-[#1E3A8A]">Изменить роль</th>
                            <th class="text-left px-6 py-3 font-medium text-[#1E3A8A]">Дата регистрации</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="user in users" :key="user.id" class="hover:bg-[#FFFFFF] transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-[#0F172A]">{{ user.name }}</div>
                                <div class="text-[#64748B] text-xs">@{{ user.username }}</div>
                            </td>
                            <td class="px-6 py-4 text-[#1E3A8A]">{{ user.email }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full" :class="roleColors[user.role]">
                                    {{ roleLabels[user.role] ?? user.role }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <select
                                    v-if="user.id !== auth.user.id"
                                    :value="user.role"
                                    @change="changeRole(user, $event.target.value)"
                                    class="border border-[#0D9488] rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] min-w-[150px]"
                                >
                                    <option value="student">Студент</option>
                                    <option value="teacher">Преподаватель</option>
                                    <option value="admin">Администратор</option>
                                </select>
                                <span v-else class="text-[#cbd5e1] text-sm">—</span>
                            </td>
                            <td class="px-6 py-4 text-[#64748B]">
                                {{ new Date(user.created_at).toLocaleDateString('ru-RU') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button v-if="user.id !== auth.user.id" @click="deleteUser(user)" class="text-[#EF4444] hover:text-[#EF4444] text-sm font-medium transition">
                                    Удалить
                                </button>
                                <span v-else class="text-[#cbd5e1] text-sm">Вы</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <AppFooter class="mt-10" />
    </div>
</template>

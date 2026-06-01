<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({ users: Array });
const { auth } = usePage().props;

const roleLabels = {
    student: 'Студент',
    teacher: 'Преподаватель',
    admin: 'Администратор',
};

const roleColors = {
    student: 'bg-blue-100 text-blue-700',
    teacher: 'bg-purple-100 text-purple-700',
    admin: 'bg-red-100 text-red-700',
};

const changeRole = (user, newRole) => {
    if (newRole === user.role) return;
    const isDowngrade = (user.role === 'teacher' || user.role === 'admin') && newRole === 'student';
    const isUpgrade = user.role === 'student' && (newRole === 'teacher' || newRole === 'admin');
    const warn = isDowngrade ? ' Все его курсы будут скрыты.' : isUpgrade ? ' Его скрытые курсы будут восстановлены.' : '';
    if (confirm(`Изменить роль "${user.name}" на "${roleLabels[newRole]}"?${warn}`)) {
        useForm({ role: newRole }).patch(route('admin.users.role', user.id));
    }
};

const deleteUser = (user) => {
    if (confirm(`Удалить аккаунт "${user.name}"? ${user.role === 'teacher' || user.role === 'admin' ? 'Все его курсы будут скрыты.' : ''}`)) {
        useForm({}).delete(route('admin.users.destroy', user.id));
    }
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">

        <!-- Хедер -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-indigo-600">EduPlatform</Link>
                <div class="flex gap-4 items-center">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Курсы</Link>
                    <Link :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Кабинет</Link>
                    <Link :href="route('admin.users')" class="bg-indigo-600 border border-indigo-600 text-white px-4 py-2 rounded transition">Пользователи</Link>
                    <Link :href="route('logout')" method="post" as="button" class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#64748b_0%,#475569_100%)] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(71,85,105,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_-18px_rgba(71,85,105,1)]">Выйти</Link>
                </div>
            </div>
        </nav>

        <!-- Контент -->
        <div class="flex-1 max-w-5xl mx-auto w-full px-4 py-10">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Управление пользователями</h1>
                <span class="text-sm text-gray-400">Всего: {{ users.length }}</span>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-gray-500">Пользователь</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500">Email</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500">Роль</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500">Изменить роль</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500">Дата регистрации</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ user.name }}</div>
                                <div class="text-gray-400 text-xs">@{{ user.username }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ user.email }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full" :class="roleColors[user.role]">
                                    {{ roleLabels[user.role] ?? user.role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ new Date(user.created_at).toLocaleDateString('ru-RU') }}
                            </td>
                            <td class="px-6 py-4">
                                <select v-if="user.id !== auth.user.id"
                                    :value="user.role"
                                    @change="changeRole(user, $event.target.value)"
                                    class="border border-gray-300 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 min-w-[150px]">
                                    <option value="student">Студент</option>
                                    <option value="teacher">Преподаватель</option>
                                    <option value="admin">Администратор</option>
                                </select>
                                <span v-else class="text-gray-300 text-sm">—</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button v-if="user.id !== auth.user.id"
                                    @click="deleteUser(user)"
                                    class="text-red-500 hover:text-red-700 text-sm font-medium transition">
                                    Удалить
                                </button>
                                <span v-else class="text-gray-300 text-sm">Вы</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Футер -->
        <footer class="bg-gray-900 py-6 text-center text-gray-400 text-sm mt-10">
            EduPlatform 2026
        </footer>

    </div>
</template>

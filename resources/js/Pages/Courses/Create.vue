<script setup>
import { useForm, Link, usePage } from '@inertiajs/vue3';

const { auth } = usePage().props;

const form = useForm({ title: '', description: '', category: '', level: '' });

const submit = () => form.post(route('courses.store'));
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

        <div class="flex-1 max-w-5xl mx-auto w-full px-4 py-10">
            <section class="overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
                <div class="border-b border-slate-200 bg-[linear-gradient(135deg,#eef2ff_0%,#ffffff_58%,#f8fafc_100%)] px-6 py-8 lg:px-10">
                    <Link :href="route('dashboard')" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 transition hover:text-indigo-700">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">&larr;</span>
                        Назад в кабинет
                    </Link>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                            Новый курс
                        </span>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-slate-200">
                            Панель преподавателя
                        </span>
                    </div>
                    <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950">Создать курс</h1>
                    <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                        Заполните основную информацию о курсе. После сохранения вы сможете добавить уроки, настроить структуру и подготовить программу к публикации.
                    </p>
                </div>

                <div class="grid gap-0 lg:grid-cols-[minmax(0,1fr)_280px]">
                    <div class="px-6 py-8 lg:px-10">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Название курса</label>
                                <input v-model="form.title" type="text" required
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                                    placeholder="Например: Основы Python" />
                                <p v-if="form.errors.title" class="mt-2 text-sm text-red-500">{{ form.errors.title }}</p>
                            </div>

                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Описание</label>
                                <textarea v-model="form.description" rows="6"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                                    placeholder="Опишите, чему научится студент, как устроен курс и для кого он подходит."></textarea>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Категория</label>
                                    <select v-model="form.category"
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100">
                                        <option value="">— Не выбрано —</option>
                                        <option value="programming">Языки программирования</option>
                                        <option value="frameworks">Фреймворки</option>
                                        <option value="design">Дизайн</option>
                                    </select>
                                </div>

                                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Уровень</label>
                                    <select v-model="form.level"
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100">
                                        <option value="">— Не выбрано —</option>
                                        <option value="beginner">Новичок</option>
                                        <option value="intermediate">Средний</option>
                                        <option value="advanced">Продвинутый</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 pt-2">
                                <button type="submit" :disabled="form.processing"
                                    class="inline-flex items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.95)] disabled:translate-y-0 disabled:opacity-50">
                                    Создать курс
                                </button>
                                <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                    Отмена
                                </Link>
                            </div>
                        </form>
                    </div>

                    <aside class="border-t border-slate-200 bg-slate-50/70 px-6 py-8 lg:border-l lg:border-t-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Подсказка</p>
                        <div class="mt-4 rounded-3xl border border-slate-200 bg-white p-5">
                            <h2 class="text-base font-semibold text-slate-900">Хорошее описание курса</h2>
                            <p class="mt-2 text-sm leading-7 text-slate-600">
                                Кратко объясняет цель обучения, ожидаемый результат и целевую аудиторию. Это помогает студенту быстрее понять ценность программы.
                            </p>
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

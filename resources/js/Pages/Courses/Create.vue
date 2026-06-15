<script setup>
import { useForm, Link, usePage } from '@inertiajs/vue3';
import AppFooter from '@/Components/AppFooter.vue';

const { auth } = usePage().props;

const form = useForm({ title: '', description: '', category: '', level: '' });

const submit = () => form.post(route('courses.store'));
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

        <div class="flex-1 max-w-5xl mx-auto w-full px-4 py-10">
            <section class="overflow-hidden rounded-[30px] border border-[#F8FAFC] bg-white shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
                <div class="border-b border-[#F8FAFC] bg-white px-6 py-8 lg:px-10">
                    <Link :href="route('dashboard')" class="inline-flex items-center gap-2 text-sm font-medium text-[#1E3A8A] transition hover:text-[#0F172A]">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#F8FAFC] text-[#1E3A8A]">&larr;</span>
                        Назад в кабинет
                    </Link>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-[#1E3A8A] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                            Новый курс
                        </span>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-[#1E3A8A] ring-1 ring-[#F8FAFC]">
                            Панель преподавателя
                        </span>
                    </div>
                    <h1 class="mt-5 text-4xl font-bold tracking-tight text-[#0F172A]">Создать курс</h1>
                    <p class="mt-3 max-w-2xl text-base leading-7 text-[#1E3A8A]">
                        Заполните основную информацию о курсе. После сохранения вы сможете добавить уроки, настроить структуру и подготовить программу к публикации.
                    </p>
                </div>

                <div class="grid gap-0 lg:grid-cols-[minmax(0,1fr)_280px]">
                    <div class="px-6 py-8 lg:px-10">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-6">
                                <label class="block text-sm font-semibold text-[#0F172A] mb-2">Название курса</label>
                                <input v-model="form.title" type="text" required
                                    class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]"
                                    placeholder="Например: Основы Python" />
                                <p v-if="form.errors.title" class="mt-2 text-sm text-[#EF4444]">{{ form.errors.title }}</p>
                            </div>

                            <div class="rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-6">
                                <label class="block text-sm font-semibold text-[#0F172A] mb-2">Описание</label>
                                <textarea v-model="form.description" rows="6"
                                    class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]"
                                    placeholder="Опишите, чему научится студент, как устроен курс и для кого он подходит."></textarea>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <div class="rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-6">
                                    <label class="block text-sm font-semibold text-[#0F172A] mb-2">Категория</label>
                                    <select v-model="form.category"
                                        class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]">
                                        <option value="">— Не выбрано —</option>
                                        <option value="programming">Языки программирования</option>
                                        <option value="frameworks">Фреймворки</option>
                                        <option value="design">Дизайн</option>
                                    </select>
                                </div>

                                <div class="rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-6">
                                    <label class="block text-sm font-semibold text-[#0F172A] mb-2">Уровень</label>
                                    <select v-model="form.level"
                                        class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]">
                                        <option value="">— Не выбрано —</option>
                                        <option value="beginner">Новичок</option>
                                        <option value="intermediate">Средний</option>
                                        <option value="advanced">Продвинутый</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 pt-2">
                                <button type="submit" :disabled="form.processing"
                                    class="inline-flex items-center justify-center rounded-2xl bg-[#F59E0B] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.95)] disabled:translate-y-0 disabled:opacity-50">
                                    Создать курс
                                </button>
                                <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-2xl border border-[#F8FAFC] bg-white px-5 py-3 text-sm font-medium text-[#0F172A] transition hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A]">
                                    Отмена
                                </Link>
                            </div>
                        </form>
                    </div>

                    <aside class="border-t border-[#F8FAFC] bg-[#FFFFFF]/70 px-6 py-8 lg:border-l lg:border-t-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Подсказка</p>
                        <div class="mt-4 rounded-3xl border border-[#F8FAFC] bg-white p-5">
                            <h2 class="text-base font-semibold text-[#0F172A]">Хорошее описание курса</h2>
                            <p class="mt-2 text-sm leading-7 text-[#1E3A8A]">
                                Кратко объясняет цель обучения, ожидаемый результат и целевую аудиторию. Это помогает студенту быстрее понять ценность программы.
                            </p>
                        </div>
                    </aside>
                </div>
            </section>
        </div>

        <AppFooter class="mt-10" />
    </div>
</template>

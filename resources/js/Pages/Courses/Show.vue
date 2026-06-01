<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    course: Object,
    isEnrolled: Boolean,
    canManage: Boolean,
    canEnroll: Boolean,
});

const { auth } = usePage().props;

const enrollForm = useForm({});
const blockForm = useForm({});

const enroll = () => enrollForm.post(route('courses.enroll', props.course.id));
const unenroll = () => enrollForm.delete(route('courses.unenroll', props.course.id));
const blockCourse = () => {
    if (confirm('Заблокировать курс "' + props.course.title + '"? Он будет скрыт из каталога.')) {
        blockForm.patch(route('courses.block', props.course.id));
    }
};
</script>

<template>
    <div class="min-h-screen bg-slate-100 flex flex-col">

        <!-- Хедер -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-indigo-600">EduPlatform</Link>
                <div class="flex gap-4 items-center">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Курсы</Link>
                    <template v-if="auth.user">
                        <Link :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Кабинет</Link>
                        <Link :href="route('logout')" method="post" as="button" class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#64748b_0%,#475569_100%)] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(71,85,105,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_-18px_rgba(71,85,105,1)]">Выйти</Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#64748b_0%,#475569_100%)] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(71,85,105,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_-18px_rgba(71,85,105,1)]">Войти</Link>
                        <Link :href="route('register')" class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-4 py-2 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(79,70,229,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(79,70,229,1)]">Регистрация</Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Контент -->
        <div class="flex-1 max-w-6xl mx-auto w-full px-4 py-10">
            <Link :href="route('courses.index')" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-indigo-600 transition hover:text-indigo-700">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">&larr;</span>
                Все курсы
            </Link>

            <section class="overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
                <div class="grid gap-0 lg:grid-cols-[minmax(0,1.2fr)_340px]">
                    <div class="bg-[linear-gradient(135deg,#eef2ff_0%,#ffffff_58%,#f8fafc_100%)] px-6 py-8 lg:px-10 lg:py-10">
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                                Курс
                            </span>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-slate-200">
                                {{ course.lessons?.length ?? 0 }} уроков
                            </span>
                        </div>

                        <h1 class="mt-5 max-w-3xl text-4xl font-bold tracking-tight text-slate-950">{{ course.title }}</h1>
                        <p class="mt-4 max-w-2xl text-lg leading-8 text-slate-600">{{ course.description }}</p>

                        <div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-slate-500">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 ring-1 ring-slate-200">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                Преподаватель: {{ course.teacher?.name }}
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 ring-1 ring-slate-200">
                                <span class="text-amber-400">★</span>
                                Рейтинг {{ Number(course.rating ?? 0).toFixed(1) }}
                            </span>
                        </div>
                    </div>

                    <aside class="border-t border-slate-200 bg-slate-50/70 px-6 py-8 lg:border-l lg:border-t-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Действия</p>
                        <div class="mt-4 space-y-3">
                            <template v-if="auth.user">
                                <div v-if="canManage" class="space-y-3">
                                    <Link :href="route('courses.edit', course.id)" class="inline-flex w-full items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.95)]">
                                        Редактировать
                                    </Link>
                                    <button v-if="auth.user?.role === 'admin' && course.is_published" @click="blockCourse" class="inline-flex w-full items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#dc2626_0%,#ef4444_100%)] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(220,38,38,0.75)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(220,38,38,0.9)]">
                                        Заблокировать курс
                                    </button>
                                </div>
                                <button v-else-if="canEnroll && !isEnrolled" @click="enroll" class="inline-flex w-full items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.95)]">
                                    Записаться на курс
                                </button>
                                <div v-else-if="canEnroll" class="rounded-3xl border border-emerald-100 bg-emerald-50 p-4">
                                    <p class="text-sm font-semibold text-emerald-700">Вы уже записаны</p>
                                    <button @click="unenroll" class="mt-2 text-sm font-medium text-red-500 transition hover:text-red-600">
                                        Отписаться от курса
                                    </button>
                                </div>
                            </template>
                            <template v-else>
                                <Link :href="route('login')" class="inline-flex w-full items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.95)]">
                                    Войти для записи
                                </Link>
                            </template>
                        </div>

                        <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Что внутри</p>
                            <p class="mt-2 text-sm leading-7 text-slate-600">
                                Курс включает последовательные уроки с теорией, примерами и возможностью отслеживать прогресс прохождения.
                            </p>
                        </div>
                    </aside>
                </div>

                <div class="border-t border-slate-200 px-6 py-8 lg:px-10">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Программа обучения</p>
                            <h2 class="mt-2 text-2xl font-semibold text-slate-900">Уроки курса</h2>
                        </div>
                        <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">
                            {{ course.lessons?.length ?? 0 }} занятий
                        </span>
                    </div>

                    <div v-if="course.lessons?.length === 0" class="mt-6 rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-6 text-slate-500">
                        Уроки ещё не добавлены
                    </div>

                    <div v-else class="mt-6 grid gap-3">
                        <div v-for="(lesson, i) in course.lessons" :key="lesson.id"
                            class="group flex items-center gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-4 transition hover:border-indigo-200 hover:bg-indigo-50/60">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-sm font-semibold text-indigo-600 ring-1 ring-slate-200 transition group-hover:ring-indigo-200">
                                {{ i + 1 }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-base font-medium text-slate-900">{{ lesson.title }}</p>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ isEnrolled ? 'Доступен для изучения' : 'Доступ откроется после записи на курс' }}
                                </p>
                            </div>

                            <span v-if="!isEnrolled" class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-slate-200">
                                Закрыт
                            </span>
                            <Link v-else :href="route('lessons.show', { course: course.id, lesson: lesson.id })"
                                class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                Открыть
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Футер -->
        <footer class="bg-gray-900 py-6 text-center text-gray-400 text-sm mt-10">
            EduPlatform 2026
        </footer>

    </div>
</template>

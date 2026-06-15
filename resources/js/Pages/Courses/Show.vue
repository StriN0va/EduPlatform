<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import AppFooter from '@/Components/AppFooter.vue';

const props = defineProps({
    course: Object,
    isEnrolled: Boolean,
    canManage: Boolean,
    canEnroll: Boolean,
    userRating: Number,
});

const { auth } = usePage().props;

const enrollForm = useForm({});
const blockForm = useForm({});
const ratingForm = useForm({
    rating: props.userRating ?? null,
});

const enroll = () => enrollForm.post(route('courses.enroll', props.course.id));
const unenroll = () => enrollForm.delete(route('courses.unenroll', props.course.id));
const rateCourse = (rating) => {
    ratingForm.rating = rating;
    ratingForm.post(route('courses.rate', props.course.id), {
        preserveScroll: true,
    });
};
const blockCourse = () => {
    if (confirm('Заблокировать курс "' + props.course.title + '"? Он будет скрыт из каталога.')) {
        blockForm.patch(route('courses.block', props.course.id));
    }
};
</script>

<template>
    <div class="min-h-screen bg-[#F8FAFC] flex flex-col">

        <!-- Хедер -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-[#1E3A8A]">EduPlatform</Link>
                <div class="flex gap-4 items-center">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Курсы</Link>
                    <template v-if="auth.user">
                        <Link :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Кабинет</Link>
                        <Link :href="route('logout')" method="post" as="button" class="inline-flex items-center justify-center rounded-full bg-[#1E3A8A] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(15,23,42,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_-18px_rgba(15,23,42,1)]">Выйти</Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="inline-flex items-center justify-center rounded-full bg-[#1E3A8A] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(15,23,42,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_-18px_rgba(15,23,42,1)]">Войти</Link>
                        <Link :href="route('register')" class="inline-flex items-center justify-center rounded-full bg-[#F59E0B] px-4 py-2 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(30,58,138,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(30,58,138,1)]">Регистрация</Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Контент -->
        <div class="flex-1 max-w-6xl mx-auto w-full px-4 py-10">
            <Link :href="route('courses.index')" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-[#1E3A8A] transition hover:text-[#0F172A]">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#F8FAFC] text-[#1E3A8A]">&larr;</span>
                Все курсы
            </Link>

            <section class="overflow-hidden rounded-[30px] border border-[#F8FAFC] bg-white shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
                <div class="grid gap-0 lg:grid-cols-[minmax(0,1.2fr)_340px]">
                    <div class="bg-white px-6 py-8 lg:px-10 lg:py-10">
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-[#1E3A8A] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                                Курс
                            </span>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-[#1E3A8A] ring-1 ring-[#F8FAFC]">
                                {{ course.lessons?.length ?? 0 }} уроков
                            </span>
                        </div>

                        <h1 class="mt-5 max-w-3xl text-4xl font-bold tracking-tight text-[#0F172A]">{{ course.title }}</h1>
                        <p class="mt-4 max-w-2xl text-lg leading-8 text-[#1E3A8A]">{{ course.description }}</p>

                        <div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-[#1E3A8A]">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 ring-1 ring-[#F8FAFC]">
                                <span class="h-2 w-2 rounded-full bg-[#0D9488]"></span>
                                Преподаватель: {{ course.teacher?.name }}
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 ring-1 ring-[#F8FAFC]">
                                <span class="text-[#F59E0B]">★</span>
                                Рейтинг {{ Number(course.rating ?? 0).toFixed(1) }}
                            </span>
                        </div>
                    </div>

                    <aside class="border-t border-[#F8FAFC] bg-[#FFFFFF]/70 px-6 py-8 lg:border-l lg:border-t-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Действия</p>
                        <div class="mt-4 space-y-3">
                            <template v-if="auth.user">
                                <div v-if="canManage" class="space-y-3">
                                    <Link :href="route('courses.edit', course.id)" class="inline-flex w-full items-center justify-center rounded-2xl bg-[#F59E0B] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.95)]">
                                        Редактировать
                                    </Link>
                                    <button v-if="auth.user?.role === 'admin' && course.is_published" @click="blockCourse" class="inline-flex w-full items-center justify-center rounded-2xl bg-[#EF4444] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(220,38,38,0.75)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(220,38,38,0.9)]">
                                        Заблокировать курс
                                    </button>
                                </div>
                                <button v-else-if="canEnroll && !isEnrolled" @click="enroll" class="inline-flex w-full items-center justify-center rounded-2xl bg-[#F59E0B] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.95)]">
                                    Записаться на курс
                                </button>
                                <div v-else-if="canEnroll" class="rounded-3xl border border-[#ccfbf1] bg-[#ecfdf5] p-4">
                                    <p class="text-sm font-semibold text-[#0D9488]">Вы уже записаны</p>
                                    <button @click="unenroll" class="mt-2 text-sm font-medium text-[#EF4444] transition hover:text-[#EF4444]">
                                        Отписаться от курса
                                    </button>
                                </div>
                            </template>
                            <template v-else>
                                <Link :href="route('login')" class="inline-flex w-full items-center justify-center rounded-2xl bg-[#F59E0B] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.95)]">
                                    Войти для записи
                                </Link>
                            </template>
                        </div>

                        <div class="mt-6 rounded-3xl border border-[#F8FAFC] bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Что внутри</p>
                            <p class="mt-2 text-sm leading-7 text-[#1E3A8A]">
                                Курс включает последовательные уроки с теорией, примерами и возможностью отслеживать прогресс прохождения.
                            </p>
                        </div>

                        <div v-if="auth.user && canEnroll && isEnrolled" class="mt-6 rounded-3xl border border-amber-100 bg-[#fff7ed] p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#F59E0B]">Оценка курса</p>
                            <p class="mt-2 text-sm text-[#1E3A8A]">Поставьте оценку после знакомства с материалами.</p>
                            <div class="mt-4 flex gap-2">
                                <button
                                    v-for="star in [1, 2, 3, 4, 5]"
                                    :key="star"
                                    type="button"
                                    @click="rateCourse(star)"
                                    class="text-2xl transition hover:-translate-y-0.5"
                                    :class="Number(ratingForm.rating ?? userRating ?? 0) >= star ? 'text-[#F59E0B]' : 'text-[#cbd5e1]'"
                                >
                                    ★
                                </button>
                            </div>
                            <p v-if="userRating" class="mt-3 text-xs text-[#1E3A8A]">Ваша текущая оценка: {{ userRating }}</p>
                        </div>
                    </aside>
                </div>

                <div class="border-t border-[#F8FAFC] px-6 py-8 lg:px-10">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Программа обучения</p>
                            <h2 class="mt-2 text-2xl font-semibold text-[#0F172A]">Уроки курса</h2>
                        </div>
                        <span class="rounded-full bg-[#F8FAFC] px-4 py-2 text-sm font-medium text-[#1E3A8A]">
                            {{ course.lessons?.length ?? 0 }} занятий
                        </span>
                    </div>

                    <div v-if="course.lessons?.length === 0" class="mt-6 rounded-3xl border border-dashed border-[#0D9488] bg-[#FFFFFF] p-6 text-[#1E3A8A]">
                        Уроки ещё не добавлены
                    </div>

                    <div v-else class="mt-6 grid gap-3">
                        <div v-for="(lesson, i) in course.lessons" :key="lesson.id"
                            class="group flex items-center gap-4 rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-4 transition hover:border-[#0D9488] hover:bg-[#F8FAFC]/60">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-sm font-semibold text-[#1E3A8A] ring-1 ring-[#F8FAFC] transition group-hover:ring-[#0D9488]">
                                {{ i + 1 }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-base font-medium text-[#0F172A]">{{ lesson.title }}</p>
                                <p class="mt-1 text-sm text-[#1E3A8A]">
                                    {{ isEnrolled ? 'Доступен для изучения' : 'Доступ откроется после записи на курс' }}
                                </p>
                            </div>

                            <span v-if="!isEnrolled" class="rounded-full bg-white px-3 py-1 text-xs font-medium text-[#1E3A8A] ring-1 ring-[#F8FAFC]">
                                Закрыт
                            </span>
                            <Link v-else :href="route('lessons.show', { course: course.id, lesson: lesson.id })"
                                class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white px-4 py-2 text-sm font-medium text-[#0F172A] transition hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A]">
                                Открыть
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Футер -->
        <AppFooter class="mt-10" />

    </div>
</template>

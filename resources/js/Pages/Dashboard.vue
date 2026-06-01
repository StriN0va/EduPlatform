<script setup>
import { Link, usePage } from '@inertiajs/vue3';

defineProps({
    enrolledCourses: Array,
    teacherCourses: Array,
});

const { auth } = usePage().props;

const isTeacher = auth.user?.role === 'teacher' || auth.user?.role === 'admin';
const canStudy = auth.user?.role !== 'admin';

const levelLabels = {
    beginner: 'Новичок',
    intermediate: 'Средний',
    advanced: 'Продвинутый',
};

const categoryLabels = {
    frameworks: 'Фреймворки',
    programming: 'Языки программирования',
    design: 'Дизайн',
};
</script>

<template>
    <div class="min-h-screen bg-slate-100 flex flex-col">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-indigo-600">EduPlatform</Link>
                <div class="flex gap-4 items-center">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Курсы</Link>
                    <Link :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-4 py-2 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(79,70,229,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(79,70,229,1)]">Кабинет</Link>
                    <Link v-if="auth.user?.role === 'admin'" :href="route('admin.users')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Пользователи</Link>
                    <Link :href="route('logout')" method="post" as="button" class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#64748b_0%,#475569_100%)] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(71,85,105,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_-18px_rgba(71,85,105,1)]">Выйти</Link>
                </div>
            </div>
        </nav>

        <div class="flex-1 max-w-7xl mx-auto w-full px-4 py-10 space-y-8">
            <section class="overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
                <div class="grid gap-0 lg:grid-cols-[minmax(0,1.15fr)_320px]">
                    <div class="bg-[linear-gradient(135deg,#eef2ff_0%,#ffffff_58%,#f8fafc_100%)] px-6 py-8 lg:px-10">
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                                Личный кабинет
                            </span>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-slate-200">
                                {{ auth.user.role === 'admin' ? 'Администратор' : auth.user.role === 'teacher' ? 'Преподаватель' : 'Студент' }}
                            </span>
                        </div>

                        <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950">
                            Добро пожаловать, {{ auth.user.name }}
                        </h1>
                        <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                            Здесь собраны ваши курсы, учебный прогресс и быстрые переходы к управлению профилем и учебными материалами.
                        </p>
                    </div>

                    <aside class="border-t border-slate-200 bg-slate-50/70 px-6 py-8 lg:border-l lg:border-t-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Быстрые действия</p>
                        <div class="mt-4 space-y-3">
                            <Link :href="route('profile.edit')" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                Настройки профиля
                            </Link>
                            <Link v-if="isTeacher" :href="route('courses.create')" class="inline-flex w-full items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.95)]">
                                Создать курс
                            </Link>
                            <Link v-else-if="canStudy" :href="route('courses.index')" class="inline-flex w-full items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.95)]">
                                Найти новые курсы
                            </Link>
                        </div>
                    </aside>
                </div>
            </section>

            <template v-if="isTeacher">
                <section class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Преподаватель</p>
                            <h2 class="mt-2 text-3xl font-bold text-slate-900">Мои курсы</h2>
                        </div>
                        <Link :href="route('courses.create')" class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-5 py-2.5 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(79,70,229,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(79,70,229,1)]">
                            Создать новый курс
                        </Link>
                    </div>

                    <div v-if="teacherCourses.length === 0" class="rounded-[28px] border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500 shadow-sm">
                        <p class="text-xl font-medium text-slate-700">У вас пока нет курсов</p>
                        <p class="mt-2">Создайте первый курс и начните собирать собственную программу обучения.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                        <div v-for="course in teacherCourses" :key="course.id" class="flex flex-col overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_18px_40px_-30px_rgba(15,23,42,0.35)] transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-[0_28px_60px_-34px_rgba(79,70,229,0.35)]">
                            <div class="bg-[linear-gradient(135deg,#eef2ff_0%,#ffffff_55%,#f8fafc_100%)] p-5">
                                <div class="flex gap-2 flex-wrap">
                                    <span v-if="course.category" class="rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-700">
                                        {{ categoryLabels[course.category] ?? course.category }}
                                    </span>
                                    <span v-if="course.level" class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                        {{ levelLabels[course.level] ?? course.level }}
                                    </span>
                                </div>
                                <h3 class="mt-4 text-xl font-semibold text-slate-900">{{ course.title }}</h3>
                            </div>

                            <div class="flex flex-1 flex-col p-5">
                                <div class="mb-5 flex gap-4 text-sm text-slate-500">
                                    <span>{{ course.lessons_count }} уроков</span>
                                    <span>{{ course.enrollments_count }} студентов</span>
                                </div>

                                <div class="mt-auto flex gap-3">
                                    <Link :href="route('courses.edit', course.id)" class="flex-1 rounded-2xl bg-indigo-50 py-2.5 text-center text-sm font-medium text-indigo-700 transition hover:bg-indigo-100">
                                        Редактировать
                                    </Link>
                                    <Link :href="route('courses.show', course.id)" class="flex-1 rounded-2xl border border-slate-200 bg-white py-2.5 text-center text-sm font-medium text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                        Просмотр
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </template>

            <template v-if="canStudy">
                <section class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Обучение</p>
                            <h2 class="mt-2 text-3xl font-bold text-slate-900">{{ isTeacher ? 'Курсы, на которые я записан' : 'Мои курсы' }}</h2>
                        </div>
                        <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/90 px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                            Найти новые курсы
                        </Link>
                    </div>

                    <div v-if="enrolledCourses.length === 0" class="rounded-[28px] border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500 shadow-sm">
                        <p class="text-xl font-medium text-slate-700">Вы ещё не записаны ни на один курс</p>
                        <p class="mt-2">Перейдите в каталог и выберите первую программу обучения.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                        <div v-for="course in enrolledCourses" :key="course.id" class="flex flex-col overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_18px_40px_-30px_rgba(15,23,42,0.35)] transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-[0_28px_60px_-34px_rgba(79,70,229,0.35)]">
                            <div class="bg-[linear-gradient(135deg,#eef2ff_0%,#ffffff_55%,#f8fafc_100%)] p-5">
                                <div class="flex gap-2 flex-wrap">
                                    <span v-if="course.category" class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                                        {{ categoryLabels[course.category] ?? course.category }}
                                    </span>
                                    <span v-if="course.level" class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                        {{ levelLabels[course.level] ?? course.level }}
                                    </span>
                                </div>
                                <h3 class="mt-4 text-xl font-semibold text-slate-900">{{ course.title }}</h3>
                                <p class="mt-2 text-sm text-slate-500">{{ course.teacher?.name }}</p>
                            </div>

                            <div class="flex flex-1 flex-col p-5">
                                <div class="mb-4">
                                    <div class="mb-2 flex justify-between text-sm text-slate-500">
                                        <span>Прогресс</span>
                                        <span>{{ course.completed_lessons }}/{{ course.total_lessons }} уроков</span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-slate-200">
                                        <div class="h-full rounded-full bg-[linear-gradient(90deg,#6366f1_0%,#22c55e_100%)] transition-all"
                                            :style="{ width: course.progress + '%' }"></div>
                                    </div>
                                    <p class="mt-2 text-right text-sm font-medium text-indigo-600">{{ course.progress }}%</p>
                                </div>

                                <Link v-if="course.first_lesson_id"
                                    :href="route('lessons.show', { course: course.id, lesson: course.first_lesson_id })"
                                    class="mt-auto inline-flex items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.95)]">
                                    {{ course.progress > 0 ? 'Продолжить' : 'Начать' }}
                                </Link>
                                <Link v-else :href="route('courses.show', course.id)"
                                    class="mt-auto inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white py-3 text-sm font-medium text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                    Перейти к курсу
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>
            </template>
        </div>

        <footer class="bg-gray-900 py-6 text-center text-gray-400 text-sm mt-10">
            EduPlatform 2026
        </footer>
    </div>
</template>

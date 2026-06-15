<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AppFooter from '@/Components/AppFooter.vue';

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
    <div class="min-h-screen bg-[#F8FAFC] flex flex-col">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-[#1E3A8A]">EduPlatform</Link>
                <div class="flex gap-4 items-center">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Курсы</Link>
                    <Link :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full bg-[#F59E0B] px-4 py-2 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(30,58,138,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(30,58,138,1)]">Кабинет</Link>
                    <Link v-if="auth.user?.role === 'admin'" :href="route('admin.users')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Пользователи</Link>
                    <Link :href="route('logout')" method="post" as="button" class="inline-flex items-center justify-center rounded-full bg-[#1E3A8A] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(15,23,42,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_-18px_rgba(15,23,42,1)]">Выйти</Link>
                </div>
            </div>
        </nav>

        <div class="flex-1 max-w-7xl mx-auto w-full px-4 py-10 space-y-8">
            <section class="overflow-hidden rounded-[30px] border border-[#F8FAFC] bg-white shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
                <div class="grid gap-0 lg:grid-cols-[minmax(0,1.15fr)_320px]">
                    <div class="bg-white px-6 py-8 lg:px-10">
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-[#1E3A8A] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                                Личный кабинет
                            </span>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-[#1E3A8A] ring-1 ring-[#F8FAFC]">
                                {{ auth.user.role === 'admin' ? 'Администратор' : auth.user.role === 'teacher' ? 'Преподаватель' : 'Студент' }}
                            </span>
                        </div>

                        <h1 class="mt-5 text-4xl font-bold tracking-tight text-[#0F172A]">
                            Добро пожаловать, {{ auth.user.name }}
                        </h1>
                        <p class="mt-3 max-w-2xl text-base leading-7 text-[#1E3A8A]">
                            Здесь собраны ваши курсы, учебный прогресс и быстрые переходы к управлению профилем и учебными материалами.
                        </p>
                    </div>

                    <aside class="border-t border-[#F8FAFC] bg-[#FFFFFF]/70 px-6 py-8 lg:border-l lg:border-t-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Быстрые действия</p>
                        <div class="mt-4 space-y-3">
                            <Link :href="route('profile.edit')" class="inline-flex w-full items-center justify-center rounded-2xl border border-[#F8FAFC] bg-white px-5 py-3 text-sm font-medium text-[#0F172A] transition hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A]">
                                Настройки профиля
                            </Link>
                            <Link v-if="isTeacher" :href="route('courses.create')" class="inline-flex w-full items-center justify-center rounded-2xl bg-[#F59E0B] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.95)]">
                                Создать курс
                            </Link>
                            <Link v-else-if="canStudy" :href="route('courses.index')" class="inline-flex w-full items-center justify-center rounded-2xl bg-[#F59E0B] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.95)]">
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
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Преподаватель</p>
                            <h2 class="mt-2 text-3xl font-bold text-[#0F172A]">Мои курсы</h2>
                        </div>
                        <Link :href="route('courses.create')" class="inline-flex items-center justify-center rounded-full bg-[#F59E0B] px-5 py-2.5 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(30,58,138,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(30,58,138,1)]">
                            Создать новый курс
                        </Link>
                    </div>

                    <div v-if="teacherCourses.length === 0" class="rounded-[28px] border border-dashed border-[#0D9488] bg-white p-10 text-center text-[#1E3A8A] shadow-sm">
                        <p class="text-xl font-medium text-[#0F172A]">У вас пока нет курсов</p>
                        <p class="mt-2">Создайте первый курс и начните собирать собственную программу обучения.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                        <div v-for="course in teacherCourses" :key="course.id" class="flex flex-col overflow-hidden rounded-[28px] border border-[#F8FAFC] bg-white shadow-[0_18px_40px_-30px_rgba(15,23,42,0.35)] transition hover:-translate-y-1 hover:border-[#0D9488] hover:shadow-[0_28px_60px_-34px_rgba(30,58,138,0.35)]">
                            <div class="bg-white p-5">
                                <div class="flex gap-2 flex-wrap">
                                    <span v-if="course.category" class="rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-700">
                                        {{ categoryLabels[course.category] ?? course.category }}
                                    </span>
                                    <span v-if="course.level" class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                        {{ levelLabels[course.level] ?? course.level }}
                                    </span>
                                </div>
                                <h3 class="mt-4 text-xl font-semibold text-[#0F172A]">{{ course.title }}</h3>
                            </div>

                            <div class="flex flex-1 flex-col p-5">
                                <div class="mb-5 flex gap-4 text-sm text-[#1E3A8A]">
                                    <span>{{ course.lessons_count }} уроков</span>
                                    <span>{{ course.enrollments_count }} студентов</span>
                                </div>

                                <div class="mt-auto flex gap-3">
                                    <Link :href="route('courses.edit', course.id)" class="flex-1 rounded-2xl bg-[#F8FAFC] py-2.5 text-center text-sm font-medium text-[#0F172A] transition hover:bg-[#F8FAFC]">
                                        Редактировать
                                    </Link>
                                    <Link :href="route('courses.show', course.id)" class="flex-1 rounded-2xl border border-[#F8FAFC] bg-white py-2.5 text-center text-sm font-medium text-[#0F172A] transition hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A]">
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
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Обучение</p>
                            <h2 class="mt-2 text-3xl font-bold text-[#0F172A]">{{ isTeacher ? 'Курсы, на которые я записан' : 'Мои курсы' }}</h2>
                        </div>
                        <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/90 px-5 py-2.5 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A]">
                            Найти новые курсы
                        </Link>
                    </div>

                    <div v-if="enrolledCourses.length === 0" class="rounded-[28px] border border-dashed border-[#0D9488] bg-white p-10 text-center text-[#1E3A8A] shadow-sm">
                        <p class="text-xl font-medium text-[#0F172A]">Вы ещё не записаны ни на один курс</p>
                        <p class="mt-2">Перейдите в каталог и выберите первую программу обучения.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                        <div v-for="course in enrolledCourses" :key="course.id" class="flex flex-col overflow-hidden rounded-[28px] border border-[#F8FAFC] bg-white shadow-[0_18px_40px_-30px_rgba(15,23,42,0.35)] transition hover:-translate-y-1 hover:border-[#0D9488] hover:shadow-[0_28px_60px_-34px_rgba(30,58,138,0.35)]">
                            <div class="bg-white p-5">
                                <div class="flex gap-2 flex-wrap">
                                    <span v-if="course.category" class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                                        {{ categoryLabels[course.category] ?? course.category }}
                                    </span>
                                    <span v-if="course.level" class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                        {{ levelLabels[course.level] ?? course.level }}
                                    </span>
                                </div>
                                <h3 class="mt-4 text-xl font-semibold text-[#0F172A]">{{ course.title }}</h3>
                                <p class="mt-2 text-sm text-[#1E3A8A]">{{ course.teacher?.name }}</p>
                            </div>

                            <div class="flex flex-1 flex-col p-5">
                                <div class="mb-4">
                                    <div class="mb-2 flex justify-between text-sm text-[#1E3A8A]">
                                        <span>Прогресс</span>
                                        <span>{{ course.completed_lessons }}/{{ course.total_lessons }} уроков</span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-[#F8FAFC]">
                                        <div class="h-full rounded-full bg-[#0D9488] transition-all"
                                            :style="{ width: course.progress + '%' }"></div>
                                    </div>
                                    <p class="mt-2 text-right text-sm font-medium text-[#1E3A8A]">{{ course.progress }}%</p>
                                </div>

                                <Link v-if="course.first_lesson_id"
                                    :href="route('lessons.show', { course: course.id, lesson: course.first_lesson_id })"
                                    class="mt-auto inline-flex items-center justify-center rounded-2xl bg-[#F59E0B] py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.95)]">
                                    {{ course.progress > 0 ? 'Продолжить' : 'Начать' }}
                                </Link>
                                <Link v-else :href="route('courses.show', course.id)"
                                    class="mt-auto inline-flex items-center justify-center rounded-2xl border border-[#F8FAFC] bg-white py-3 text-sm font-medium text-[#0F172A] transition hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A]">
                                    Перейти к курсу
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>
            </template>
        </div>

        <AppFooter class="mt-10" />
    </div>
</template>

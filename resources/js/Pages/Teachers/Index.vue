<script setup>
import { Link, usePage } from '@inertiajs/vue3';

defineProps({
    teachers: Array,
});

const { auth } = usePage().props;

const initials = (name) => {
    return (name ?? '')
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0])
        .join('')
        .toUpperCase();
};
</script>

<template>
    <div class="min-h-screen bg-slate-100 flex flex-col">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-indigo-600">EduPlatform</Link>
                <div class="flex gap-4">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Курсы</Link>
                    <Link :href="route('teachers.index')" class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-4 py-2 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(79,70,229,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(79,70,229,1)]">Преподаватели</Link>
                    <Link v-if="auth.user" :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Кабинет</Link>
                    <Link v-else :href="route('login')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Войти</Link>
                </div>
            </div>
        </nav>

        <main class="flex-1">
            <section class="max-w-7xl mx-auto px-4 pt-16 pb-10">
                <div class="max-w-3xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-indigo-500">Наша команда</p>
                    <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-950 lg:text-5xl">Наши преподаватели</h1>
                    <p class="mt-5 text-lg leading-8 text-slate-600">
                        На платформе работают преподаватели, которые ведут собственные курсы и делятся практическим опытом.
                        Здесь собраны специалисты, у которых можно изучать материалы по шагам и в удобном темпе.
                    </p>
                </div>
            </section>

            <section class="max-w-7xl mx-auto px-4 pb-20">
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    <article v-for="teacher in teachers" :key="teacher.id" class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_20px_50px_-34px_rgba(15,23,42,0.35)]">
                        <div class="aspect-[4/3] bg-slate-100 p-4">
                            <img v-if="teacher.image" :src="teacher.image" :alt="teacher.name" class="h-full w-full rounded-[20px] object-contain object-center" />
                            <div v-else class="flex h-full items-center justify-center rounded-[20px] bg-[linear-gradient(135deg,#e2e8f0_0%,#f8fafc_100%)] text-4xl font-semibold text-slate-500">
                                {{ initials(teacher.name) }}
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600">
                                Преподаватель
                            </div>
                            <h2 class="mt-4 text-2xl font-semibold text-slate-900">{{ teacher.name }}</h2>
                            <p v-if="teacher.username" class="mt-2 text-sm text-slate-500">@{{ teacher.username }}</p>
                            <p class="mt-4 text-sm leading-7 text-slate-600">{{ teacher.email }}</p>
                            <div class="mt-6 flex items-center justify-between border-t border-slate-200 pt-4 text-sm text-slate-500">
                                <span>Курсов на платформе</span>
                                <span class="font-semibold text-slate-900">{{ teacher.courses_count }}</span>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        </main>

        <footer class="bg-gray-900 py-6 text-center text-gray-400 text-sm">
            EduPlatform 2026
        </footer>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AppFooter from '@/Components/AppFooter.vue';

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
    <div class="min-h-screen bg-[#F8FAFC] flex flex-col">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-[#1E3A8A]">EduPlatform</Link>
                <div class="flex gap-4">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Курсы</Link>
                    <Link :href="route('teachers.index')" class="inline-flex items-center justify-center rounded-full bg-[#F59E0B] px-4 py-2 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(30,58,138,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(30,58,138,1)]">Преподаватели</Link>
                    <Link v-if="auth.user" :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Кабинет</Link>
                    <Link v-else :href="route('login')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Войти</Link>
                </div>
            </div>
        </nav>

        <main class="flex-1">
            <section class="max-w-7xl mx-auto px-4 pt-16 pb-10">
                <div class="max-w-3xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Наша команда</p>
                    <h1 class="mt-4 text-4xl font-bold tracking-tight text-[#0F172A] lg:text-5xl">Наши преподаватели</h1>
                    <p class="mt-5 text-lg leading-8 text-[#1E3A8A]">
                        На платформе работают преподаватели, которые ведут собственные курсы и делятся практическим опытом.
                        Здесь собраны специалисты, у которых можно изучать материалы по шагам и в удобном темпе.
                    </p>
                </div>
            </section>

            <section class="max-w-7xl mx-auto px-4 pb-20">
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    <article v-for="teacher in teachers" :key="teacher.id" class="overflow-hidden rounded-[28px] border border-[#F8FAFC] bg-white shadow-[0_20px_50px_-34px_rgba(15,23,42,0.35)]">
                        <div class="aspect-[4/3] bg-[#F8FAFC] p-4">
                            <img v-if="teacher.image" :src="teacher.image" :alt="teacher.name" class="h-full w-full rounded-[20px] object-contain object-center" />
                            <div v-else class="flex h-full items-center justify-center rounded-[20px] bg-[#F8FAFC] text-4xl font-semibold text-[#1E3A8A]">
                                {{ initials(teacher.name) }}
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="inline-flex rounded-full bg-[#F8FAFC] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-[#1E3A8A]">
                                Преподаватель
                            </div>
                            <h2 class="mt-4 text-2xl font-semibold text-[#0F172A]">{{ teacher.name }}</h2>
                            <p v-if="teacher.username" class="mt-2 text-sm text-[#1E3A8A]">@{{ teacher.username }}</p>
                            <p class="mt-4 text-sm leading-7 text-[#1E3A8A]">{{ teacher.email }}</p>
                            <div class="mt-6 flex items-center justify-between border-t border-[#F8FAFC] pt-4 text-sm text-[#1E3A8A]">
                                <span>Курсов на платформе</span>
                                <span class="font-semibold text-[#0F172A]">{{ teacher.courses_count }}</span>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        </main>

        <AppFooter />
    </div>
</template>

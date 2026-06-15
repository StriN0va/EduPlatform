<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AppFooter from '@/Components/AppFooter.vue';

const { auth } = usePage().props;

const steps = [
    {
        number: '01',
        title: 'Зарегистрируйся',
        description: 'Создай аккаунт и открой доступ к каталогу и личному кабинету.',
    },
    {
        number: '02',
        title: 'Выбери курс',
        description: 'Найди подходящую программу и начни изучение материалов.',
    },
    {
        number: '03',
        title: 'Учись в своем темпе',
        description: 'Проходи уроки последовательно и возвращайся к нужным темам.',
    },
    {
        number: '04',
        title: 'Следи за прогрессом',
        description: 'Отмечай завершенные уроки и контролируй продвижение по курсу.',
    },
];
</script>

<template>
    <div class="min-h-screen bg-[#F8FAFC] overflow-hidden">
        <nav class="relative z-10 bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <span class="text-2xl font-bold text-[#1E3A8A]">EduPlatform</span>
                <div class="flex gap-4">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Курсы</Link>
                    <template v-if="auth.user">
                        <Link :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Кабинет</Link>
                        <Link :href="route('logout')" method="post" as="button" class="inline-flex items-center justify-center rounded-full bg-[#1E3A8A] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(15,23,42,0.9)] transition hover:-translate-y-0.5 hover:bg-[#0F172A] hover:shadow-[0_18px_30px_-18px_rgba(15,23,42,1)]">Выйти</Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="inline-flex items-center justify-center rounded-full bg-[#1E3A8A] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(15,23,42,0.9)] transition hover:-translate-y-0.5 hover:bg-[#0F172A] hover:shadow-[0_18px_30px_-18px_rgba(15,23,42,1)]">Войти</Link>
                        <Link :href="route('register')" class="inline-flex items-center justify-center rounded-full bg-[#F59E0B] px-4 py-2 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(30,58,138,0.95)] transition hover:-translate-y-0.5 hover:bg-[#d97706] hover:shadow-[0_22px_34px_-18px_rgba(30,58,138,1)]">Регистрация</Link>
                    </template>
                </div>
            </div>
        </nav>

        <div class="relative z-10 max-w-7xl mx-auto px-4 pt-24 pb-12 lg:pt-32">
            <div class="grid items-center gap-10 lg:grid-cols-[minmax(0,1.15fr)_420px]">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-[#0D9488]"></span>
                        Онлайн-обучение в одном кабинете
                    </div>
                    <h1 class="mt-6 text-5xl font-bold leading-tight text-[#0F172A] lg:text-6xl">
                        Учись. Развивайся.<br>
                        <span class="text-[#1E3A8A]">Достигай.</span>
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-[#1E3A8A]">
                        Образовательная платформа с курсами по программированию, дизайну и другим направлениям.
                        Изучай материалы в удобном темпе, записывайся на курсы и отслеживай прогресс в одном интерфейсе.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-2xl bg-[#F59E0B] px-8 py-4 text-lg font-semibold text-white shadow-[0_24px_40px_-24px_rgba(30,58,138,0.9)] transition hover:-translate-y-0.5 hover:bg-[#d97706] hover:shadow-[0_30px_46px_-24px_rgba(30,58,138,1)]">
                            Смотреть курсы
                        </Link>
                        <Link v-if="!auth.user" :href="route('register')" class="inline-flex items-center justify-center rounded-2xl border border-[#F8FAFC] bg-white/90 px-8 py-4 text-lg font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A]">
                            Создать аккаунт
                        </Link>
                    </div>
                </div>

                <div class="rounded-[32px] border border-white/70 bg-white/80 p-6 shadow-[0_30px_70px_-40px_rgba(15,23,42,0.45)] backdrop-blur">
                    <div class="rounded-[28px] bg-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Личный кабинет</p>
                                <h2 class="mt-2 text-2xl font-semibold text-[#0F172A]">Прогресс обучения</h2>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div class="rounded-2xl border border-[#F8FAFC] bg-white p-4 shadow-sm">
                                <div class="flex items-center justify-between text-sm text-[#1E3A8A]">
                                    <span>Основы PHP</span>
                                    <span>72%</span>
                                </div>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-[#F8FAFC]">
                                    <div class="h-full w-[72%] rounded-full bg-[#0D9488]"></div>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-[#F8FAFC] bg-white p-4 shadow-sm">
                                <div class="flex items-center justify-between text-sm text-[#1E3A8A]">
                                    <span>Vue.js для начинающих</span>
                                    <span>41%</span>
                                </div>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-[#F8FAFC]">
                                    <div class="h-full w-[41%] rounded-full bg-[#0D9488]"></div>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-[#F8FAFC] bg-white p-4 shadow-sm">
                                <div class="flex items-center justify-between text-sm text-[#1E3A8A]">
                                    <span>UI-дизайн</span>
                                    <span>18%</span>
                                </div>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-[#F8FAFC]">
                                    <div class="h-full w-[18%] rounded-full bg-[#0D9488]"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 pt-10 pb-20 grid grid-cols-1 md:grid-cols-3 gap-8">
            <Link
                :href="route('courses.index')"
                class="rounded-[28px] border border-[#F8FAFC] bg-white/90 p-8 shadow-[0_20px_40px_-30px_rgba(15,23,42,0.35)] backdrop-blur transition cursor-pointer hover:-translate-y-1 hover:border-[#0D9488] hover:bg-[#F8FAFC]/80 hover:shadow-[0_24px_46px_-30px_rgba(30,58,138,0.35)]"
            >
                <h3 class="mb-3 text-xl font-semibold text-[#0F172A]">Курсы на любой вкус</h3>
                <p class="text-[#1E3A8A] leading-7">Программирование, дизайн и другие направления в одном каталоге.</p>
            </Link>

            <div class="rounded-[28px] border border-[#F8FAFC] bg-white/90 p-8 shadow-[0_20px_40px_-30px_rgba(15,23,42,0.35)] backdrop-blur">
                <h3 class="mb-3 text-xl font-semibold text-[#0F172A]">Отслеживай прогресс</h3>
                <p class="text-[#1E3A8A] leading-7">Смотри статус обучения и возвращайся к материалам в удобный момент.</p>
            </div>

            <Link
                :href="route('teachers.index')"
                class="rounded-[28px] border border-[#F8FAFC] bg-white/90 p-8 shadow-[0_20px_40px_-30px_rgba(15,23,42,0.35)] backdrop-blur transition cursor-pointer hover:-translate-y-1 hover:border-[#0D9488] hover:bg-[#F8FAFC]/80 hover:shadow-[0_24px_46px_-30px_rgba(30,58,138,0.35)]"
            >
                <h3 class="mb-3 text-xl font-semibold text-[#0F172A]">Опытные преподаватели</h3>
                <p class="text-[#1E3A8A] leading-7">Познакомься с преподавателями платформы и их направлениями обучения.</p>
            </Link>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 pb-28">
            <h2 class="mb-14 text-center text-3xl font-bold text-[#0F172A]">Как это работает</h2>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <div v-for="step in steps" :key="step.number" class="rounded-[28px] border border-[#F8FAFC] bg-white/90 p-6 shadow-[0_20px_40px_-30px_rgba(15,23,42,0.25)]">
                    <div class="inline-flex rounded-full bg-[#F8FAFC] px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-[#1E3A8A]">
                        Шаг {{ step.number }}
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-[#0F172A]">{{ step.title }}</h3>
                    <p class="mt-3 text-sm leading-7 text-[#1E3A8A]">{{ step.description }}</p>
                </div>
            </div>
        </div>

        <AppFooter class="relative z-10" />
    </div>
</template>


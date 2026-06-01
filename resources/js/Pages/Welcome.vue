<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const { auth } = usePage().props;
</script>

<template>
    <div class="min-h-screen bg-slate-100 overflow-hidden">
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>
        </div>

        <nav class="relative z-10 bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <span class="text-2xl font-bold text-indigo-600">EduPlatform</span>
                <div class="flex gap-4">
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

        <div class="relative z-10 max-w-7xl mx-auto px-4 pt-24 pb-12 lg:pt-32">
            <div class="grid items-center gap-10 lg:grid-cols-[minmax(0,1.15fr)_420px]">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-white/80 px-4 py-2 text-sm font-medium text-indigo-700 shadow-sm backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Онлайн-обучение в одном кабинете
                    </div>
                    <h1 class="mt-6 text-5xl font-bold leading-tight text-slate-950 lg:text-6xl">
                        Учись. Развивайся.<br>
                <span class="text-indigo-600">Достигай.</span>
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                        Образовательная платформа с курсами по программированию, дизайну и другим направлениям. Изучайте материалы в удобном темпе, записывайтесь на курсы и отслеживайте свой прогресс в одном интерфейсе.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-8 py-4 text-lg font-semibold text-white shadow-[0_24px_40px_-24px_rgba(79,70,229,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_30px_46px_-24px_rgba(79,70,229,1)]">
                            Смотреть курсы
                        </Link>
                        <Link v-if="!auth.user" :href="route('register')" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white/90 px-8 py-4 text-lg font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                            Создать аккаунт
                        </Link>
                    </div>
                </div>

                <div class="rounded-[32px] border border-white/70 bg-white/80 p-6 shadow-[0_30px_70px_-40px_rgba(15,23,42,0.45)] backdrop-blur">
                    <div class="rounded-[28px] bg-[linear-gradient(160deg,#eef2ff_0%,#ffffff_52%,#e2e8f0_100%)] p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-indigo-400">Личный кабинет</p>
                                <h2 class="mt-2 text-2xl font-semibold text-slate-900">Прогресс обучения</h2>
                            </div>
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-2xl shadow-sm ring-1 ring-slate-100">
                                📚
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-center justify-between text-sm text-slate-500">
                                    <span>Основы PHP</span>
                                    <span>72%</span>
                                </div>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full w-[72%] rounded-full bg-[linear-gradient(90deg,#6366f1_0%,#22c55e_100%)]"></div>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-center justify-between text-sm text-slate-500">
                                    <span>Vue.js для начинающих</span>
                                    <span>41%</span>
                                </div>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full w-[41%] rounded-full bg-[linear-gradient(90deg,#6366f1_0%,#22c55e_100%)]"></div>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-center justify-between text-sm text-slate-500">
                                    <span>UI-дизайн</span>
                                    <span>18%</span>
                                </div>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full w-[18%] rounded-full bg-[linear-gradient(90deg,#6366f1_0%,#22c55e_100%)]"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 pt-10 pb-20 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="rounded-[28px] border border-slate-200 bg-white/90 p-8 shadow-[0_20px_40px_-30px_rgba(15,23,42,0.35)] text-center backdrop-blur">
                <div class="mb-4 text-4xl">📚</div>
                <h3 class="mb-2 text-xl font-semibold text-slate-900">Курсы на любой вкус</h3>
                <p class="text-slate-600">Программирование, дизайн, маркетинг и многое другое</p>
            </div>
            <div class="rounded-[28px] border border-slate-200 bg-white/90 p-8 shadow-[0_20px_40px_-30px_rgba(15,23,42,0.35)] text-center backdrop-blur">
                <div class="mb-4 text-4xl">📊</div>
                <h3 class="mb-2 text-xl font-semibold text-slate-900">Отслеживай прогресс</h3>
                <p class="text-slate-600">Видь свой прогресс по каждому курсу в личном кабинете</p>
            </div>
            <div class="rounded-[28px] border border-slate-200 bg-white/90 p-8 shadow-[0_20px_40px_-30px_rgba(15,23,42,0.35)] text-center backdrop-blur">
                <div class="mb-4 text-4xl">🎓</div>
                <h3 class="mb-2 text-xl font-semibold text-slate-900">Опытные преподаватели</h3>
                <p class="text-slate-600">Учись у практикующих специалистов своего дела</p>
            </div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 pb-28">
            <h2 class="mb-14 text-center text-3xl font-bold text-slate-900">Как это работает</h2>
            <div class="flex items-start">
                <div class="flex flex-col items-center text-center flex-1">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-indigo-600 text-2xl shadow-md shadow-indigo-200">
                        🖊️
                    </div>
                    <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1">Шаг 1</div>
                    <h3 class="mb-1 font-semibold text-slate-800">Зарегистрируйся</h3>
                    <p class="max-w-[140px] text-sm text-slate-500">Создай аккаунт бесплатно за пару минут</p>
                </div>
                <div class="flex-shrink-0 flex items-center mt-7">
                    <svg class="w-8 h-8 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5l6 7.5-6 7.5M4.5 12h15" />
                    </svg>
                </div>
                <div class="flex flex-col items-center text-center flex-1">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-indigo-600 text-2xl shadow-md shadow-indigo-200">
                        🔍
                    </div>
                    <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1">Шаг 2</div>
                    <h3 class="mb-1 font-semibold text-slate-800">Выбери курс</h3>
                    <p class="max-w-[140px] text-sm text-slate-500">Найди подходящий курс в каталоге и запишись</p>
                </div>
                <div class="flex-shrink-0 flex items-center mt-7">
                    <svg class="w-8 h-8 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5l6 7.5-6 7.5M4.5 12h15" />
                    </svg>
                </div>
                <div class="flex flex-col items-center text-center flex-1">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-indigo-600 text-2xl shadow-md shadow-indigo-200">
                        🎯
                    </div>
                    <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1">Шаг 3</div>
                    <h3 class="mb-1 font-semibold text-slate-800">Учись в своём темпе</h3>
                    <p class="max-w-[140px] text-sm text-slate-500">Проходи уроки в удобное для тебя время</p>
                </div>
                <div class="flex-shrink-0 flex items-center mt-7">
                    <svg class="w-8 h-8 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5l6 7.5-6 7.5M4.5 12h15" />
                    </svg>
                </div>
                <div class="flex flex-col items-center text-center flex-1">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-indigo-600 text-2xl shadow-md shadow-indigo-200">
                        🏆
                    </div>
                    <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1">Шаг 4</div>
                    <h3 class="mb-1 font-semibold text-slate-800">Отслеживай успехи</h3>
                    <p class="max-w-[140px] text-sm text-slate-500">Следи за прогрессом в личном кабинете</p>
                </div>
            </div>
        </div>
        <footer class="relative z-10 bg-gray-900 py-6 text-center text-gray-400 text-sm">
            EduPlatform 2026
        </footer>
    </div>
</template>

<style scoped>
.blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.35;
    animation: blobFloat 20s ease-in-out infinite;
}

.blob-1 {
    width: 600px;
    height: 600px;
    background: #818cf8;
    top: -150px;
    left: -150px;
    animation-delay: 0s;
    animation-duration: 22s;
}

.blob-2 {
    width: 500px;
    height: 500px;
    background: #a5b4fc;
    bottom: 100px;
    right: -100px;
    animation-delay: -7s;
    animation-duration: 26s;
}

.blob-3 {
    width: 350px;
    height: 350px;
    background: #c7d2fe;
    top: 40%;
    left: 40%;
    animation-delay: -14s;
    animation-duration: 30s;
}

@keyframes blobFloat {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33%       { transform: translate(30px, -30px) scale(1.04); }
    66%       { transform: translate(-20px, 20px) scale(0.97); }
}
</style>

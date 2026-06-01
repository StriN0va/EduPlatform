<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({ courses: Array });

const { auth } = usePage().props;

const selectedCategories = ref([]);
const selectedLevels = ref([]);
const selectedRating = ref(null);

const categoryLabels = {
    frameworks: 'Фреймворки',
    programming: 'Языки программирования',
    design: 'Дизайн',
};

const levelLabels = {
    beginner: 'Новичок',
    intermediate: 'Средний',
    advanced: 'Продвинутый',
};

const levelColors = {
    beginner: 'bg-green-100 text-green-700',
    intermediate: 'bg-yellow-100 text-yellow-700',
    advanced: 'bg-red-100 text-red-700',
};

const categoryColors = {
    frameworks: 'bg-purple-100 text-purple-700',
    programming: 'bg-blue-100 text-blue-700',
    design: 'bg-pink-100 text-pink-700',
};

const filteredCourses = computed(() => {
    return props.courses.filter(course => {
        if (selectedCategories.value.length && !selectedCategories.value.includes(course.category)) return false;
        if (selectedLevels.value.length && !selectedLevels.value.includes(course.level)) return false;
        if (selectedRating.value && course.rating < selectedRating.value) return false;
        return true;
    });
});

function toggleCategory(val) {
    const i = selectedCategories.value.indexOf(val);
    if (i === -1) selectedCategories.value.push(val);
    else selectedCategories.value.splice(i, 1);
}

function toggleLevel(val) {
    const i = selectedLevels.value.indexOf(val);
    if (i === -1) selectedLevels.value.push(val);
    else selectedLevels.value.splice(i, 1);
}

function resetFilters() {
    selectedCategories.value = [];
    selectedLevels.value = [];
    selectedRating.value = null;
}

function stars(rating) {
    return Math.round(rating);
}
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-indigo-600">EduPlatform</Link>
                <div class="flex gap-4 items-center">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-4 py-2 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(79,70,229,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(79,70,229,1)]">Курсы</Link>
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

        <div class="flex-1 max-w-7xl mx-auto px-4 py-10 flex gap-8 w-full">

            <!-- Сайдбар фильтров -->
            <aside class="w-72 flex-shrink-0">
                <div class="sticky top-6 rounded-[28px] border border-slate-200 bg-white/90 p-6 shadow-[0_20px_40px_-30px_rgba(15,23,42,0.35)] backdrop-blur">
                    <div class="flex justify-between items-center mb-5">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Навигация</p>
                            <h2 class="mt-1 text-lg font-bold text-slate-900">Фильтры</h2>
                        </div>
                        <button @click="resetFilters" class="text-xs font-medium text-indigo-500 transition hover:text-indigo-700">Сбросить</button>
                    </div>

                    <!-- Категория -->
                    <div class="mb-6">
                        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Категория</h3>
                        <div class="space-y-2">
                            <label v-for="(label, val) in categoryLabels" :key="val" class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" :value="val" :checked="selectedCategories.includes(val)"
                                    @change="toggleCategory(val)"
                                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                <span class="text-sm text-slate-700 transition group-hover:text-indigo-600">{{ label }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Уровень -->
                    <div class="mb-6">
                        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Уровень</h3>
                        <div class="space-y-2">
                            <label v-for="(label, val) in levelLabels" :key="val" class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" :value="val" :checked="selectedLevels.includes(val)"
                                    @change="toggleLevel(val)"
                                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                <span class="text-sm text-slate-700 transition group-hover:text-indigo-600">{{ label }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Рейтинг -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Рейтинг</h3>
                        <div class="space-y-2">
                            <label v-for="r in [3.0, 4.0, 4.5]" :key="r" class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" :value="r" v-model="selectedRating"
                                    class="w-4 h-4 border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                <span class="flex items-center gap-1 text-sm text-slate-700 transition group-hover:text-indigo-600">
                                    <span class="text-amber-400">★</span> {{ r }}+
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Основной контент -->
            <div class="flex-1">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Все курсы
                        <span class="text-lg font-normal text-gray-400 ml-2">({{ filteredCourses.length }})</span>
                    </h1>
                    <Link v-if="auth.user?.role === 'teacher' || auth.user?.role === 'admin'" :href="route('courses.create')" class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-5 py-2.5 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(79,70,229,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(79,70,229,1)]">
                        + Создать курс
                    </Link>
                </div>

                <div v-if="filteredCourses.length === 0" class="text-center py-20 text-gray-400">
                    <div class="text-5xl mb-4">🔍</div>
                    <p class="text-xl">Курсов по выбранным фильтрам не найдено</p>
                    <button @click="resetFilters" class="mt-4 text-indigo-600 hover:underline text-sm">Сбросить фильтры</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <Link v-for="course in filteredCourses" :key="course.id"
                        :href="route('courses.show', course.id)"
                        class="group flex flex-col overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_18px_40px_-30px_rgba(15,23,42,0.35)] transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-[0_28px_60px_-34px_rgba(79,70,229,0.35)]">

                        <div class="flex h-40 items-end justify-between bg-[linear-gradient(135deg,#eef2ff_0%,#c7d2fe_45%,#f8fafc_100%)] p-5">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-indigo-500">EduPlatform</p>
                                <p class="mt-2 max-w-[10rem] text-sm font-medium leading-6 text-slate-600">
                                    Подборка материалов для последовательного изучения
                                </p>
                            </div>
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/80 text-3xl shadow-sm ring-1 ring-white/80 transition group-hover:scale-105">
                                📘
                            </div>
                        </div>

                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex gap-2 flex-wrap mb-3">
                                <span v-if="course.category"
                                    :class="categoryColors[course.category]"
                                    class="rounded-full px-2.5 py-1 text-xs font-medium">
                                    {{ categoryLabels[course.category] }}
                                </span>
                                <span v-if="course.level"
                                    :class="levelColors[course.level]"
                                    class="rounded-full px-2.5 py-1 text-xs font-medium">
                                    {{ levelLabels[course.level] }}
                                </span>
                            </div>

                            <h3 class="mb-2 text-xl font-semibold leading-snug text-slate-900 transition group-hover:text-indigo-700">{{ course.title }}</h3>
                            <p class="mb-5 flex-1 text-sm leading-7 text-slate-500 line-clamp-3">{{ course.description }}</p>

                            <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.14em] text-slate-400">Автор курса</p>
                                    <p class="mt-1 text-sm font-medium text-slate-700">{{ course.teacher?.name }}</p>
                                </div>
                                <span v-if="course.rating" class="flex items-center gap-1 rounded-full bg-amber-50 px-3 py-1 text-sm font-medium text-amber-700 ring-1 ring-amber-100">
                                    <span class="text-amber-400">★</span>
                                    {{ Number(course.rating).toFixed(1) }}
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Футер -->
        <footer class="bg-gray-900 py-6 text-center text-gray-400 text-sm mt-10">
            EduPlatform 2026
        </footer>
    </div>
</template>

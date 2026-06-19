<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AppFooter from '@/Components/AppFooter.vue';

const props = defineProps({
    courses: Array,
});

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
    advanced: 'bg-red-100 text-[#EF4444]',
};

const categoryColors = {
    frameworks: 'bg-purple-100 text-purple-700',
    programming: 'bg-blue-100 text-blue-700',
    design: 'bg-pink-100 text-pink-700',
};

const filteredCourses = computed(() => {
    return props.courses.filter((course) => {
        if (selectedCategories.value.length && !selectedCategories.value.includes(course.category)) return false;
        if (selectedLevels.value.length && !selectedLevels.value.includes(course.level)) return false;
        if (selectedRating.value && Number(course.rating) < selectedRating.value) return false;
        return true;
    });
});

function toggleCategory(value) {
    const index = selectedCategories.value.indexOf(value);
    if (index === -1) selectedCategories.value.push(value);
    else selectedCategories.value.splice(index, 1);
}

function toggleLevel(value) {
    const index = selectedLevels.value.indexOf(value);
    if (index === -1) selectedLevels.value.push(value);
    else selectedLevels.value.splice(index, 1);
}

function resetFilters() {
    selectedCategories.value = [];
    selectedLevels.value = [];
    selectedRating.value = null;
}
</script>

<template>
    <div class="min-h-screen bg-[#FFFFFF] flex flex-col">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-[#1E3A8A]">Дополнительное Образование</Link>
                <div class="flex gap-4 items-center">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full bg-[#F59E0B] px-4 py-2 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(30,58,138,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(30,58,138,1)]">Курсы</Link>
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

        <div class="flex-1 max-w-7xl mx-auto px-4 py-10 flex gap-8 w-full">
            <aside class="w-72 flex-shrink-0">
                <div class="sticky top-6 rounded-[28px] border border-[#F8FAFC] bg-white/90 p-6 shadow-[0_20px_40px_-30px_rgba(15,23,42,0.35)] backdrop-blur">
                    <div class="flex justify-between items-center mb-5">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Навигация</p>
                            <h2 class="mt-1 text-lg font-bold text-[#0F172A]">Фильтры</h2>
                        </div>
                        <button @click="resetFilters" class="text-xs font-medium text-[#0D9488] transition hover:text-[#0F172A]">Сбросить</button>
                    </div>

                    <div class="mb-6">
                        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-[#1E3A8A]">Категория</h3>
                        <div class="space-y-2">
                            <label v-for="(label, value) in categoryLabels" :key="value" class="flex items-center gap-2 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    :value="value"
                                    :checked="selectedCategories.includes(value)"
                                    @change="toggleCategory(value)"
                                    class="w-4 h-4 rounded border-[#0D9488] text-[#1E3A8A] focus:ring-[#1E3A8A] cursor-pointer"
                                >
                                <span class="text-sm text-[#0F172A] transition group-hover:text-[#1E3A8A]">{{ label }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-[#1E3A8A]">Уровень</h3>
                        <div class="space-y-2">
                            <label v-for="(label, value) in levelLabels" :key="value" class="flex items-center gap-2 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    :value="value"
                                    :checked="selectedLevels.includes(value)"
                                    @change="toggleLevel(value)"
                                    class="w-4 h-4 rounded border-[#0D9488] text-[#1E3A8A] focus:ring-[#1E3A8A] cursor-pointer"
                                >
                                <span class="text-sm text-[#0F172A] transition group-hover:text-[#1E3A8A]">{{ label }}</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-[#1E3A8A]">Рейтинг</h3>
                        <div class="space-y-2">
                            <label v-for="rating in [3.0, 4.0, 4.5]" :key="rating" class="flex items-center gap-2 cursor-pointer group">
                                <input
                                    v-model="selectedRating"
                                    type="radio"
                                    :value="rating"
                                    class="w-4 h-4 border-[#0D9488] text-[#1E3A8A] focus:ring-[#1E3A8A] cursor-pointer"
                                >
                                <span class="flex items-center gap-1 text-sm text-[#0F172A] transition group-hover:text-[#1E3A8A]">
                                    <span class="text-[#F59E0B]">★</span> {{ rating }}+
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="flex-1">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-[#0F172A]">
                        Все курсы
                        <span class="text-lg font-normal text-[#64748B] ml-2">({{ filteredCourses.length }})</span>
                    </h1>
                    <Link
                        v-if="auth.user?.role === 'teacher' || auth.user?.role === 'admin'"
                        :href="route('courses.create')"
                        class="inline-flex items-center justify-center rounded-full bg-[#F59E0B] px-5 py-2.5 text-sm font-semibold text-white shadow-[0_16px_28px_-18px_rgba(30,58,138,0.95)] transition hover:-translate-y-0.5 hover:shadow-[0_22px_34px_-18px_rgba(30,58,138,1)]"
                    >
                        + Создать курс
                    </Link>
                </div>

                <div v-if="filteredCourses.length === 0" class="text-center py-20 text-[#64748B]">
                    <p class="text-xl">Курсов по выбранным фильтрам не найдено</p>
                    <button @click="resetFilters" class="mt-4 text-[#1E3A8A] hover:underline text-sm">Сбросить фильтры</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <Link
                        v-for="course in filteredCourses"
                        :key="course.id"
                        :href="route('courses.show', course.id)"
                        class="group flex flex-col rounded-[28px] border border-[#F8FAFC] bg-white p-5 shadow-[0_18px_40px_-30px_rgba(15,23,42,0.35)] transition hover:-translate-y-1 hover:border-[#0D9488] hover:shadow-[0_28px_60px_-34px_rgba(30,58,138,0.35)]"
                    >
                        <div class="flex gap-2 flex-wrap mb-3">
                            <span
                                v-if="course.category"
                                :class="categoryColors[course.category]"
                                class="rounded-full px-2.5 py-1 text-xs font-medium"
                            >
                                {{ categoryLabels[course.category] }}
                            </span>
                            <span
                                v-if="course.level"
                                :class="levelColors[course.level]"
                                class="rounded-full px-2.5 py-1 text-xs font-medium"
                            >
                                {{ levelLabels[course.level] }}
                            </span>
                        </div>

                        <h3 class="mb-2 text-xl font-semibold leading-snug text-[#0F172A] transition group-hover:text-[#0F172A]">{{ course.title }}</h3>
                        <p class="mb-5 flex-1 text-sm leading-7 text-[#1E3A8A] line-clamp-3">{{ course.description }}</p>

                        <div class="flex items-center justify-between border-t border-[#F8FAFC] pt-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.14em] text-[#0D9488]">Автор курса</p>
                                <p class="mt-1 text-sm font-medium text-[#0F172A]">{{ course.teacher?.name }}</p>
                            </div>
                            <span v-if="course.rating" class="flex items-center gap-1 rounded-full bg-[#fff7ed] px-3 py-1 text-sm font-medium text-[#0F172A] ring-1 ring-[#fed7aa]">
                                <span class="text-[#F59E0B]">★</span>
                                {{ Number(course.rating).toFixed(1) }}
                            </span>
                        </div>
                    </Link>
                </div>
            </div>
        </div>

        <AppFooter class="mt-10" />
    </div>
</template>

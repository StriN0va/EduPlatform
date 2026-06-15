<script setup>
import { computed, ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    course: Object,
    lesson: Object,
    lessons: Array,
    completed: Boolean,
    completedLessonIds: Array,
});

const completeForm = useForm({});
const markComplete = () => completeForm.post(route('lessons.complete', { course: props.course.id, lesson: props.lesson.id }));
const selectedPracticeAnswer = ref(null);

const currentLessonIndex = computed(() => props.lessons.findIndex((item) => item.id === props.lesson.id));
const nextLesson = computed(() => props.lessons[currentLessonIndex.value + 1] ?? null);
const practiceOptions = computed(() => {
    const practice = props.lesson.practice;
    if (!practice) return [];

    return [
        { value: 'a', label: practice.option_a },
        { value: 'b', label: practice.option_b },
        { value: 'c', label: practice.option_c },
        { value: 'd', label: practice.option_d },
    ].filter((option) => option.label);
});
const practiceResult = computed(() => {
    if (!props.lesson.practice || !selectedPracticeAnswer.value) return null;

    return selectedPracticeAnswer.value === props.lesson.practice.correct_option;
});

const contentBlocks = computed(() => {
    const content = props.lesson.content ?? '';

    return content
        .split(/\r?\n\r?\n/)
        .map((block) => block.split(/\r?\n/).map((line) => line.trim()).filter(Boolean))
        .filter((lines) => lines.length > 0)
        .map((lines) => {
            const bulletLines = lines.filter((line) => line.startsWith('- ') || line.startsWith('• '));
            const allBullets = bulletLines.length === lines.length;
            const titleWithBullets = lines.length > 1 && lines[0].endsWith(':') && bulletLines.length === lines.length - 1;
            const codeLike = lines.length === 1 && /^(php|composer|npm|node|artisan|git)\b|--|::|=>|[\\/]/i.test(lines[0]);

            if (titleWithBullets) {
                return {
                    type: 'callout-list',
                    title: lines[0],
                    items: lines.slice(1).map((line) => line.replace(/^[-•]\s*/, '')),
                };
            }

            if (allBullets) {
                return {
                    type: 'list',
                    items: lines.map((line) => line.replace(/^[-•]\s*/, '')),
                };
            }

            if (codeLike) {
                return {
                    type: 'code',
                    content: lines[0],
                };
            }

            return {
                type: 'paragraph',
                content: lines.join(' '),
            };
        });
});
</script>

<template>
    <div class="min-h-screen bg-[#F8FAFC] lg:flex">
        <aside class="w-full border-b border-[#F8FAFC] bg-white/90 p-6 shadow-sm lg:w-80 lg:flex-shrink-0 lg:border-b-0 lg:border-r">
            <Link :href="route('courses.show', course.id)" class="mb-5 inline-flex items-center gap-2 text-sm font-medium text-[#1E3A8A] transition hover:text-[#0F172A]">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#F8FAFC] text-[#1E3A8A]">&larr;</span>
                {{ course.title }}
            </Link>

            <div class="mb-5 rounded-2xl border border-[#F8FAFC] bg-[#FFFFFF] p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Навигация по курсу</p>
                <h3 class="mt-2 text-lg font-semibold text-[#0F172A]">Уроки курса</h3>
                <p class="mt-1 text-sm text-[#1E3A8A]">Шаг {{ currentLessonIndex + 1 }} из {{ lessons.length }}</p>
            </div>

            <nav class="space-y-2">
                <Link v-for="(l, i) in lessons" :key="l.id"
                    :href="route('lessons.show', { course: course.id, lesson: l.id })"
                    class="flex items-start gap-3 rounded-2xl border px-4 py-3 text-sm transition"
                    :class="l.id === lesson.id
                        ? 'border-[#0D9488] bg-[#F8FAFC] text-[#0F172A] shadow-sm'
                        : 'border-transparent bg-white text-[#1E3A8A] hover:border-[#F8FAFC] hover:bg-[#FFFFFF]'">
                    <span class="mt-0.5 flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full text-xs font-semibold"
                        :class="completedLessonIds.includes(l.id)
                            ? 'bg-[#0D9488] text-white'
                            : l.id === lesson.id
                                ? 'bg-[#1E3A8A] text-white'
                                : 'bg-[#F8FAFC] text-[#1E3A8A]'">
                        {{ completedLessonIds.includes(l.id) ? '✓' : (i + 1) }}
                    </span>
                    <span class="leading-6">{{ l.title }}</span>
                </Link>
            </nav>
        </aside>

        <main class="flex-1 px-4 py-6 lg:px-10 lg:py-10">
            <div class="mx-auto max-w-4xl">
                <section class="overflow-hidden rounded-[28px] bg-white shadow-[0_20px_60px_-30px_rgba(15,23,42,0.25)] ring-1 ring-[#F8FAFC]">
                    <div class="border-b border-[#F8FAFC] bg-white px-6 py-8 lg:px-10">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="rounded-full bg-[#1E3A8A] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                                Урок {{ currentLessonIndex + 1 }}
                            </span>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-[#1E3A8A] ring-1 ring-[#F8FAFC]">
                                {{ completed ? 'Пройден' : 'В процессе' }}
                            </span>
                        </div>
                        <h1 class="mt-4 max-w-3xl text-3xl font-bold tracking-tight text-[#0F172A] lg:text-4xl">{{ lesson.title }}</h1>
                        <p class="mt-3 max-w-2xl text-base leading-7 text-[#1E3A8A]">
                            Материал урока оформлен для последовательного изучения. Проходите разделы по порядку и отмечайте урок после завершения.
                        </p>
                    </div>

                    <div class="grid gap-6 px-6 py-6 lg:grid-cols-[minmax(0,1fr)_240px] lg:px-10 lg:py-8">
                        <div class="space-y-6">
                            <div v-if="lesson.video_url" class="overflow-hidden rounded-3xl border border-[#F8FAFC] bg-[#0F172A] shadow-sm">
                                <iframe :src="lesson.video_url" class="h-80 w-full lg:h-[26rem]" allowfullscreen></iframe>
                            </div>

                            <div class="space-y-5">
                                <template v-for="(block, index) in contentBlocks" :key="index">
                                    <p v-if="block.type === 'paragraph'" class="text-lg leading-8 text-[#0F172A]">
                                        {{ block.content }}
                                    </p>

                                    <div v-else-if="block.type === 'callout-list'" class="rounded-3xl border border-[#F8FAFC] bg-[#F8FAFC]/60 p-5">
                                        <h2 class="text-base font-semibold text-[#0F172A]">{{ block.title }}</h2>
                                        <ul class="mt-3 space-y-2 text-[#0F172A]">
                                            <li v-for="item in block.items" :key="item" class="flex items-start gap-3">
                                                <span class="mt-2 h-2 w-2 flex-shrink-0 rounded-full bg-[#0D9488]"></span>
                                                <span class="leading-7">{{ item }}</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <ul v-else-if="block.type === 'list'" class="space-y-3 rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-5 text-[#0F172A]">
                                        <li v-for="item in block.items" :key="item" class="flex items-start gap-3">
                                            <span class="mt-2 h-2 w-2 flex-shrink-0 rounded-full bg-slate-400"></span>
                                            <span class="leading-7">{{ item }}</span>
                                        </li>
                                    </ul>

                                    <div v-else-if="block.type === 'code'" class="overflow-x-auto rounded-2xl bg-[#0F172A] px-4 py-3 font-mono text-sm text-slate-100 shadow-sm">
                                        {{ block.content }}
                                    </div>
                                </template>
                            </div>

                            <div v-if="lesson.practice" class="rounded-3xl border border-[#ccfbf1] bg-[#ecfdf5]/70 p-6">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Практика</p>
                                <h2 class="mt-3 text-xl font-semibold text-[#0F172A]">{{ lesson.practice.question }}</h2>

                                <div class="mt-5 space-y-3">
                                    <label
                                        v-for="option in practiceOptions"
                                        :key="option.value"
                                        class="flex cursor-pointer items-start gap-3 rounded-2xl border bg-white p-4 transition"
                                        :class="selectedPracticeAnswer === option.value
                                            ? 'border-[#5eead4] ring-2 ring-[#ccfbf1]'
                                            : 'border-[#F8FAFC] hover:border-[#99f6e4]'"
                                    >
                                        <input
                                            v-model="selectedPracticeAnswer"
                                            type="radio"
                                            :value="option.value"
                                            class="mt-1 border-[#0D9488] text-[#0D9488] focus:ring-[#0D9488]"
                                        />
                                        <span class="leading-7 text-[#0F172A]">{{ option.label }}</span>
                                    </label>
                                </div>

                                <div v-if="practiceResult !== null" class="mt-5 rounded-2xl px-4 py-3 text-sm font-medium"
                                    :class="practiceResult ? 'bg-white text-[#0D9488] ring-1 ring-[#99f6e4]' : 'bg-white text-[#EF4444] ring-1 ring-[#fee2e2]'">
                                    {{ practiceResult ? 'Ответ верный. Можно переходить дальше.' : 'Ответ неверный. Попробуйте выбрать другой вариант.' }}
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-4 border-t border-[#F8FAFC] pt-6">
                                <div v-if="completed" class="inline-flex items-center gap-2 rounded-2xl bg-[#ecfdf5] px-5 py-3 text-sm font-medium text-[#0D9488] ring-1 ring-[#ccfbf1]">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#0D9488] text-white">✓</span>
                                    Урок пройден
                                </div>
                                <button v-else @click="markComplete" :disabled="completeForm.processing"
                                    class="inline-flex items-center justify-center rounded-2xl bg-[#F59E0B] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.8)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.9)] disabled:translate-y-0 disabled:opacity-50">
                                    Отметить как пройденный
                                </button>

                                <Link v-if="nextLesson"
                                    :href="route('lessons.show', { course: course.id, lesson: nextLesson.id })"
                                    class="inline-flex items-center justify-center rounded-2xl border border-[#F8FAFC] bg-white px-5 py-3 text-sm font-semibold text-[#0F172A] transition hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A]">
                                    Следующий урок
                                </Link>
                            </div>
                        </div>

                        <aside class="space-y-4">
                            <div class="rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Прогресс</p>
                                <p class="mt-2 text-3xl font-bold text-[#0F172A]">{{ completedLessonIds.length }}/{{ lessons.length }}</p>
                                <p class="mt-1 text-sm leading-6 text-[#1E3A8A]">уроков отмечено как завершённые</p>
                                <div class="mt-4 h-2 overflow-hidden rounded-full bg-[#F8FAFC]">
                                    <div class="h-full rounded-full bg-[#0D9488]"
                                        :style="{ width: `${(completedLessonIds.length / Math.max(lessons.length, 1)) * 100}%` }"></div>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-[#F8FAFC] bg-white p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Совет</p>
                                <p class="mt-2 text-sm leading-7 text-[#1E3A8A]">
                                    Сначала изучите материал урока, затем выполните действия из примеров и только после этого отмечайте урок завершённым.
                                </p>
                            </div>
                        </aside>
                    </div>
                </section>
            </div>
        </main>
    </div>
</template>

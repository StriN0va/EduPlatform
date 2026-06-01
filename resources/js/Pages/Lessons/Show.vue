<script setup>
import { computed } from 'vue';
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

const currentLessonIndex = computed(() => props.lessons.findIndex((item) => item.id === props.lesson.id));
const nextLesson = computed(() => props.lessons[currentLessonIndex.value + 1] ?? null);

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
    <div class="min-h-screen bg-slate-100 lg:flex">
        <aside class="w-full border-b border-slate-200 bg-white/90 p-6 shadow-sm lg:w-80 lg:flex-shrink-0 lg:border-b-0 lg:border-r">
            <Link :href="route('courses.show', course.id)" class="mb-5 inline-flex items-center gap-2 text-sm font-medium text-indigo-600 transition hover:text-indigo-700">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">&larr;</span>
                {{ course.title }}
            </Link>

            <div class="mb-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Навигация по курсу</p>
                <h3 class="mt-2 text-lg font-semibold text-slate-900">Уроки курса</h3>
                <p class="mt-1 text-sm text-slate-500">Шаг {{ currentLessonIndex + 1 }} из {{ lessons.length }}</p>
            </div>

            <nav class="space-y-2">
                <Link v-for="(l, i) in lessons" :key="l.id"
                    :href="route('lessons.show', { course: course.id, lesson: l.id })"
                    class="flex items-start gap-3 rounded-2xl border px-4 py-3 text-sm transition"
                    :class="l.id === lesson.id
                        ? 'border-indigo-200 bg-indigo-50 text-indigo-700 shadow-sm'
                        : 'border-transparent bg-white text-slate-600 hover:border-slate-200 hover:bg-slate-50'">
                    <span class="mt-0.5 flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full text-xs font-semibold"
                        :class="completedLessonIds.includes(l.id)
                            ? 'bg-emerald-500 text-white'
                            : l.id === lesson.id
                                ? 'bg-indigo-600 text-white'
                                : 'bg-slate-200 text-slate-600'">
                        {{ completedLessonIds.includes(l.id) ? '✓' : (i + 1) }}
                    </span>
                    <span class="leading-6">{{ l.title }}</span>
                </Link>
            </nav>
        </aside>

        <main class="flex-1 px-4 py-6 lg:px-10 lg:py-10">
            <div class="mx-auto max-w-4xl">
                <section class="overflow-hidden rounded-[28px] bg-white shadow-[0_20px_60px_-30px_rgba(15,23,42,0.25)] ring-1 ring-slate-200">
                    <div class="border-b border-slate-200 bg-[linear-gradient(135deg,#eef2ff_0%,#ffffff_55%,#f8fafc_100%)] px-6 py-8 lg:px-10">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                                Урок {{ currentLessonIndex + 1 }}
                            </span>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-slate-200">
                                {{ completed ? 'Пройден' : 'В процессе' }}
                            </span>
                        </div>
                        <h1 class="mt-4 max-w-3xl text-3xl font-bold tracking-tight text-slate-950 lg:text-4xl">{{ lesson.title }}</h1>
                        <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                            Материал урока оформлен для последовательного изучения. Проходите разделы по порядку и отмечайте урок после завершения.
                        </p>
                    </div>

                    <div class="grid gap-6 px-6 py-6 lg:grid-cols-[minmax(0,1fr)_240px] lg:px-10 lg:py-8">
                        <div class="space-y-6">
                            <div v-if="lesson.video_url" class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-950 shadow-sm">
                                <iframe :src="lesson.video_url" class="h-80 w-full lg:h-[26rem]" allowfullscreen></iframe>
                            </div>

                            <div class="space-y-5">
                                <template v-for="(block, index) in contentBlocks" :key="index">
                                    <p v-if="block.type === 'paragraph'" class="text-lg leading-8 text-slate-700">
                                        {{ block.content }}
                                    </p>

                                    <div v-else-if="block.type === 'callout-list'" class="rounded-3xl border border-indigo-100 bg-indigo-50/60 p-5">
                                        <h2 class="text-base font-semibold text-slate-900">{{ block.title }}</h2>
                                        <ul class="mt-3 space-y-2 text-slate-700">
                                            <li v-for="item in block.items" :key="item" class="flex items-start gap-3">
                                                <span class="mt-2 h-2 w-2 flex-shrink-0 rounded-full bg-indigo-500"></span>
                                                <span class="leading-7">{{ item }}</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <ul v-else-if="block.type === 'list'" class="space-y-3 rounded-3xl border border-slate-200 bg-slate-50 p-5 text-slate-700">
                                        <li v-for="item in block.items" :key="item" class="flex items-start gap-3">
                                            <span class="mt-2 h-2 w-2 flex-shrink-0 rounded-full bg-slate-400"></span>
                                            <span class="leading-7">{{ item }}</span>
                                        </li>
                                    </ul>

                                    <div v-else-if="block.type === 'code'" class="overflow-x-auto rounded-2xl bg-slate-950 px-4 py-3 font-mono text-sm text-slate-100 shadow-sm">
                                        {{ block.content }}
                                    </div>
                                </template>
                            </div>

                            <div class="flex flex-wrap items-center gap-4 border-t border-slate-200 pt-6">
                                <div v-if="completed" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-50 px-5 py-3 text-sm font-medium text-emerald-700 ring-1 ring-emerald-100">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-500 text-white">✓</span>
                                    Урок пройден
                                </div>
                                <button v-else @click="markComplete" :disabled="completeForm.processing"
                                    class="inline-flex items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.8)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.9)] disabled:translate-y-0 disabled:opacity-50">
                                    Отметить как пройденный
                                </button>

                                <Link v-if="nextLesson"
                                    :href="route('lessons.show', { course: course.id, lesson: nextLesson.id })"
                                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                    Следующий урок
                                </Link>
                            </div>
                        </div>

                        <aside class="space-y-4">
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Прогресс</p>
                                <p class="mt-2 text-3xl font-bold text-slate-900">{{ completedLessonIds.length }}/{{ lessons.length }}</p>
                                <p class="mt-1 text-sm leading-6 text-slate-500">уроков отмечено как завершённые</p>
                                <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full rounded-full bg-[linear-gradient(90deg,#6366f1_0%,#22c55e_100%)]"
                                        :style="{ width: `${(completedLessonIds.length / Math.max(lessons.length, 1)) * 100}%` }"></div>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-slate-200 bg-white p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Совет</p>
                                <p class="mt-2 text-sm leading-7 text-slate-600">
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

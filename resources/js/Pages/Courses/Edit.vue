<script setup>
import { useForm, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const { auth } = usePage().props;

const props = defineProps({ course: Object });

const form = useForm({
    title: props.course.title,
    description: props.course.description,
    category: props.course.category ?? '',
    level: props.course.level ?? '',
});

const nextLessonOrder = (props.course.lessons?.length ?? 0) + 1;
const lessonForm = useForm({ title: '', content: '', video_url: '', order: nextLessonOrder });
const editingLessonId = ref(null);
const lessonEditForm = useForm({ title: '', content: '', video_url: '', order: 0 });

const update = () => form.patch(route('courses.update', props.course.id));
const addLesson = () => lessonForm.post(route('lessons.store', props.course.id), {
    onSuccess: () => lessonForm.reset('title', 'content', 'video_url'),
});
const editLesson = (lesson) => {
    editingLessonId.value = lesson.id;
    lessonEditForm.title = lesson.title;
    lessonEditForm.content = lesson.content ?? '';
    lessonEditForm.video_url = lesson.video_url ?? '';
    lessonEditForm.order = (lesson.order ?? 0) + 1;
};
const cancelLessonEdit = () => {
    editingLessonId.value = null;
    lessonEditForm.reset();
};
const updateLesson = (lessonId) => {
    lessonEditForm.patch(route('lessons.update', { course: props.course.id, lesson: lessonId }), {
        onSuccess: cancelLessonEdit,
    });
};
const deleteLesson = (lessonId) => {
    if (confirm('Удалить урок?')) {
        useForm({}).delete(route('lessons.destroy', { course: props.course.id, lesson: lessonId }));
    }
};

const deleteCourse = () => {
    if (confirm('Удалить курс "' + props.course.title + '"? Это действие нельзя отменить.')) {
        useForm({}).delete(route('courses.destroy', props.course.id));
    }
};
</script>

<template>
    <div class="min-h-screen bg-slate-100 flex flex-col">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-indigo-600">EduPlatform</Link>
                <div class="flex gap-4 items-center">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Курсы</Link>
                    <Link :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 hover:shadow-md">Кабинет</Link>
                    <Link :href="route('logout')" method="post" as="button" class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#64748b_0%,#475569_100%)] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(71,85,105,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_-18px_rgba(71,85,105,1)]">Выйти</Link>
                </div>
            </div>
        </nav>

        <div class="flex-1 max-w-6xl mx-auto w-full px-4 py-10 space-y-6">
            <section class="overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
                <div class="border-b border-slate-200 bg-[linear-gradient(135deg,#eef2ff_0%,#ffffff_58%,#f8fafc_100%)] px-6 py-8 lg:px-10">
                    <Link :href="route('dashboard')" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 transition hover:text-indigo-700">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">&larr;</span>
                        Назад в кабинет
                    </Link>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                            Редактор курса
                        </span>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-slate-200">
                            {{ course.lessons?.length ?? 0 }} уроков
                        </span>
                    </div>
                    <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950">Редактировать курс</h1>
                    <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                        Обновляйте описание, структуру и порядок уроков. Все изменения сразу влияют на то, как курс увидит студент.
                    </p>
                </div>

                <div class="grid gap-6 px-6 py-8 lg:grid-cols-[minmax(0,1fr)_320px] lg:px-10">
                    <div class="space-y-6">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                            <h2 class="text-lg font-semibold text-slate-900">Информация о курсе</h2>
                            <form @submit.prevent="update" class="mt-5 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Название</label>
                                    <input v-model="form.title" type="text" required
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Описание</label>
                                    <textarea v-model="form.description" rows="5"
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100"></textarea>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Категория</label>
                                        <select v-model="form.category"
                                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100">
                                            <option value="">— Не выбрано —</option>
                                            <option value="programming">Языки программирования</option>
                                            <option value="frameworks">Фреймворки</option>
                                            <option value="design">Дизайн</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Уровень</label>
                                        <select v-model="form.level"
                                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100">
                                            <option value="">— Не выбрано —</option>
                                            <option value="beginner">Новичок</option>
                                            <option value="intermediate">Средний</option>
                                            <option value="advanced">Продвинутый</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.95)]">
                                    Сохранить изменения
                                </button>
                            </form>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-6">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Структура курса</p>
                                    <h2 class="mt-2 text-xl font-semibold text-slate-900">Уроки</h2>
                                </div>
                                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">
                                    {{ course.lessons?.length ?? 0 }} уроков
                                </span>
                            </div>

                            <div class="mt-6 space-y-3">
                                <div v-for="lesson in course.lessons" :key="lesson.id" class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                    <div v-if="editingLessonId === lesson.id">
                                        <form @submit.prevent="updateLesson(lesson.id)" class="space-y-3">
                                            <input v-model="lessonEditForm.title" type="text" required
                                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                            <textarea v-model="lessonEditForm.content" rows="4"
                                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100"></textarea>
                                            <input v-model="lessonEditForm.video_url" type="url"
                                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                            <input v-model.number="lessonEditForm.order" type="number" min="1"
                                                class="w-28 rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                            <div class="flex gap-3">
                                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#4f46e5_0%,#6366f1_100%)] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(79,70,229,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(79,70,229,0.95)]">
                                                    Сохранить
                                                </button>
                                                <button type="button" @click="cancelLessonEdit" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                                    Отмена
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div v-else class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-sm font-semibold text-indigo-600 ring-1 ring-slate-200">
                                                {{ lesson.order + 1 }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-900">{{ lesson.title }}</p>
                                                <p class="mt-1 text-sm text-slate-500">Порядок отображения урока в курсе</p>
                                            </div>
                                        </div>

                                        <div class="flex gap-3">
                                            <button @click="editLesson(lesson)" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                                Редактировать
                                            </button>
                                            <button @click="deleteLesson(lesson.id)" class="inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100">
                                                Удалить
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <p v-if="!course.lessons?.length" class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-6 text-slate-500">
                                    Уроков ещё нет
                                </p>
                            </div>
                        </div>
                    </div>

                    <aside class="space-y-6">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Новый урок</p>
                            <h2 class="mt-2 text-lg font-semibold text-slate-900">Добавить материал</h2>
                            <form @submit.prevent="addLesson" class="mt-5 space-y-3">
                                <input v-model="lessonForm.title" type="text" required placeholder="Название урока"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                <textarea v-model="lessonForm.content" rows="4" placeholder="Текст урока..."
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100"></textarea>
                                <input v-model="lessonForm.video_url" type="url" placeholder="Ссылка на видео (необязательно)"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                <input v-model.number="lessonForm.order" type="number" placeholder="Порядок" min="1"
                                    class="w-28 rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-100" />
                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#16a34a_0%,#22c55e_100%)] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(34,197,94,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(34,197,94,0.95)]">
                                    Добавить урок
                                </button>
                            </form>
                        </div>

                        <div class="rounded-3xl border border-red-100 bg-white p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-red-400">Опасная зона</p>
                            <h2 class="mt-2 text-lg font-semibold text-slate-900">Удаление курса</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-500">
                                Удаление курса очистит уроки и записи студентов. Это действие нельзя отменить.
                            </p>
                            <button @click="deleteCourse" class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#dc2626_0%,#ef4444_100%)] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(220,38,38,0.75)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(220,38,38,0.9)]">
                                Удалить курс
                            </button>
                        </div>
                    </aside>
                </div>
            </section>
        </div>

        <footer class="bg-gray-900 py-6 text-center text-gray-400 text-sm mt-10">
            EduPlatform 2026
        </footer>
    </div>
</template>

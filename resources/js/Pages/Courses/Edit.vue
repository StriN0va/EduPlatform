<script setup>
import AppFooter from '@/Components/AppFooter.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const { auth } = usePage().props;
const props = defineProps({ course: Object });

const form = useForm({
    title: props.course.title,
    description: props.course.description,
    category: props.course.category ?? '',
    level: props.course.level ?? '',
});

const emptyPractice = () => ({
    practice_question: '',
    practice_option_a: '',
    practice_option_b: '',
    practice_option_c: '',
    practice_option_d: '',
    practice_correct_option: 'a',
});

const nextLessonOrder = (props.course.lessons?.length ?? 0) + 1;
const lessonForm = useForm({
    title: '',
    content: '',
    video_url: '',
    order: nextLessonOrder,
    ...emptyPractice(),
});

const editingLessonId = ref(null);
const lessonEditForm = useForm({
    title: '',
    content: '',
    video_url: '',
    order: 1,
    ...emptyPractice(),
});

const update = () => form.patch(route('courses.update', props.course.id));

const addLesson = () => lessonForm.post(route('lessons.store', props.course.id), {
    onSuccess: () => {
        lessonForm.reset();
        lessonForm.order = nextLessonOrder + 1;
        lessonForm.practice_correct_option = 'a';
    },
});

const editLesson = (lesson) => {
    editingLessonId.value = lesson.id;
    lessonEditForm.title = lesson.title;
    lessonEditForm.content = lesson.content ?? '';
    lessonEditForm.video_url = lesson.video_url ?? '';
    lessonEditForm.order = (lesson.order ?? 0) + 1;
    lessonEditForm.practice_question = lesson.practice?.question ?? '';
    lessonEditForm.practice_option_a = lesson.practice?.option_a ?? '';
    lessonEditForm.practice_option_b = lesson.practice?.option_b ?? '';
    lessonEditForm.practice_option_c = lesson.practice?.option_c ?? '';
    lessonEditForm.practice_option_d = lesson.practice?.option_d ?? '';
    lessonEditForm.practice_correct_option = lesson.practice?.correct_option ?? 'a';
};

const cancelLessonEdit = () => {
    editingLessonId.value = null;
    lessonEditForm.reset();
    lessonEditForm.practice_correct_option = 'a';
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
    if (confirm(`Удалить курс "${props.course.title}"? Это действие нельзя отменить.`)) {
        useForm({}).delete(route('courses.destroy', props.course.id));
    }
};
</script>

<template>
    <div class="min-h-screen bg-[#F8FAFC] flex flex-col">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <Link :href="route('home')" class="text-2xl font-bold text-[#1E3A8A]">EduPlatform</Link>
                <div class="flex gap-4 items-center">
                    <Link :href="route('courses.index')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Курсы</Link>
                    <Link :href="route('dashboard')" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white/80 px-4 py-2 text-sm font-medium text-[#0F172A] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A] hover:shadow-md">Кабинет</Link>
                    <Link :href="route('logout')" method="post" as="button" class="inline-flex items-center justify-center rounded-full bg-[#1E3A8A] px-4 py-2 text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(15,23,42,0.9)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_30px_-18px_rgba(15,23,42,1)]">Выйти</Link>
                </div>
            </div>
        </nav>

        <div class="flex-1 max-w-6xl mx-auto w-full px-4 py-10 space-y-6">
            <section class="overflow-hidden rounded-[30px] border border-[#F8FAFC] bg-white shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)]">
                <div class="border-b border-[#F8FAFC] bg-white px-6 py-8 lg:px-10">
                    <Link :href="route('dashboard')" class="inline-flex items-center gap-2 text-sm font-medium text-[#1E3A8A] transition hover:text-[#0F172A]">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#F8FAFC] text-[#1E3A8A]">&larr;</span>
                        Назад в кабинет
                    </Link>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-[#1E3A8A] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                            Редактор курса
                        </span>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-[#1E3A8A] ring-1 ring-[#F8FAFC]">
                            {{ course.lessons?.length ?? 0 }} уроков
                        </span>
                    </div>
                    <h1 class="mt-5 text-4xl font-bold tracking-tight text-[#0F172A]">Редактировать курс</h1>
                    <p class="mt-3 max-w-2xl text-base leading-7 text-[#1E3A8A]">
                        Обновляйте описание, структуру уроков, теорию и практические задания.
                    </p>
                </div>

                <div class="grid gap-6 px-6 py-8 lg:grid-cols-[minmax(0,1fr)_340px] lg:px-10">
                    <div class="space-y-6">
                        <div class="rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-6">
                            <h2 class="text-lg font-semibold text-[#0F172A]">Информация о курсе</h2>
                            <form @submit.prevent="update" class="mt-5 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-[#0F172A] mb-2">Название</label>
                                    <input v-model="form.title" type="text" required class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-[#0F172A] mb-2">Описание</label>
                                    <textarea v-model="form.description" rows="5" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]"></textarea>
                                </div>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-semibold text-[#0F172A] mb-2">Категория</label>
                                        <select v-model="form.category" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]">
                                            <option value="">Не выбрано</option>
                                            <option value="programming">Языки программирования</option>
                                            <option value="frameworks">Фреймворки</option>
                                            <option value="design">Дизайн</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-[#0F172A] mb-2">Уровень</label>
                                        <select v-model="form.level" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]">
                                            <option value="">Не выбрано</option>
                                            <option value="beginner">Новичок</option>
                                            <option value="intermediate">Средний</option>
                                            <option value="advanced">Продвинутый</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[#F59E0B] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.95)]">
                                    Сохранить изменения
                                </button>
                            </form>
                        </div>

                        <div class="rounded-3xl border border-[#F8FAFC] bg-white p-6">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Структура курса</p>
                                    <h2 class="mt-2 text-xl font-semibold text-[#0F172A]">Уроки</h2>
                                </div>
                                <span class="rounded-full bg-[#F8FAFC] px-4 py-2 text-sm font-medium text-[#1E3A8A]">
                                    {{ course.lessons?.length ?? 0 }} уроков
                                </span>
                            </div>

                            <div class="mt-6 space-y-3">
                                <div v-for="lesson in course.lessons" :key="lesson.id" class="rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-4">
                                    <form v-if="editingLessonId === lesson.id" @submit.prevent="updateLesson(lesson.id)" class="space-y-4">
                                        <input v-model="lessonEditForm.title" type="text" required class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                        <textarea v-model="lessonEditForm.content" rows="5" placeholder="Теория урока" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]"></textarea>
                                        <input v-model="lessonEditForm.video_url" type="url" placeholder="Ссылка на видео" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                        <input v-model.number="lessonEditForm.order" type="number" min="1" class="w-28 rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />

                                        <div class="rounded-3xl border border-[#F8FAFC] bg-[#F8FAFC]/70 p-5">
                                            <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-[#1E3A8A]">Практическое задание</h3>
                                            <div class="mt-4 space-y-3">
                                                <textarea v-model="lessonEditForm.practice_question" rows="3" placeholder="Вопрос" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]"></textarea>
                                                <input v-model="lessonEditForm.practice_option_a" type="text" placeholder="Вариант A" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                                <input v-model="lessonEditForm.practice_option_b" type="text" placeholder="Вариант B" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                                <input v-model="lessonEditForm.practice_option_c" type="text" placeholder="Вариант C" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                                <input v-model="lessonEditForm.practice_option_d" type="text" placeholder="Вариант D" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                                <select v-model="lessonEditForm.practice_correct_option" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]">
                                                    <option value="a">Правильный ответ: A</option>
                                                    <option value="b">Правильный ответ: B</option>
                                                    <option value="c">Правильный ответ: C</option>
                                                    <option value="d">Правильный ответ: D</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="flex gap-3">
                                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-[#F59E0B] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(30,58,138,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(30,58,138,0.95)]">
                                                Сохранить
                                            </button>
                                            <button type="button" @click="cancelLessonEdit" class="inline-flex items-center justify-center rounded-2xl border border-[#F8FAFC] bg-white px-5 py-3 text-sm font-medium text-[#0F172A] transition hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A]">
                                                Отмена
                                            </button>
                                        </div>
                                    </form>

                                    <div v-else class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-sm font-semibold text-[#1E3A8A] ring-1 ring-[#F8FAFC]">
                                                {{ lesson.order + 1 }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-[#0F172A]">{{ lesson.title }}</p>
                                                <p class="mt-1 text-sm text-[#1E3A8A]">
                                                    {{ lesson.practice ? 'Есть практическое задание' : 'Только теория' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex gap-3">
                                            <button @click="editLesson(lesson)" class="inline-flex items-center justify-center rounded-full border border-[#F8FAFC] bg-white px-4 py-2 text-sm font-medium text-[#0F172A] transition hover:border-[#0D9488] hover:bg-[#F8FAFC] hover:text-[#0F172A]">
                                                Редактировать
                                            </button>
                                            <button @click="deleteLesson(lesson.id)" class="inline-flex items-center justify-center rounded-full border border-red-200 bg-[#fef2f2] px-4 py-2 text-sm font-medium text-[#EF4444] transition hover:bg-red-100">
                                                Удалить
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <p v-if="!course.lessons?.length" class="rounded-3xl border border-dashed border-[#0D9488] bg-[#FFFFFF] p-6 text-[#1E3A8A]">
                                    Уроков еще нет
                                </p>
                            </div>
                        </div>
                    </div>

                    <aside class="space-y-6">
                        <div class="rounded-3xl border border-[#F8FAFC] bg-[#FFFFFF] p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0D9488]">Новый урок</p>
                            <h2 class="mt-2 text-lg font-semibold text-[#0F172A]">Добавить материал</h2>
                            <form @submit.prevent="addLesson" class="mt-5 space-y-3">
                                <input v-model="lessonForm.title" type="text" required placeholder="Название урока" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                <textarea v-model="lessonForm.content" rows="4" placeholder="Теория урока..." class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]"></textarea>
                                <input v-model="lessonForm.video_url" type="url" placeholder="Ссылка на видео" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                <input v-model.number="lessonForm.order" type="number" placeholder="Порядок" min="1" class="w-28 rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />

                                <div class="rounded-3xl border border-[#F8FAFC] bg-white p-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#0D9488]">Практика</p>
                                    <div class="mt-3 space-y-3">
                                        <textarea v-model="lessonForm.practice_question" rows="3" placeholder="Вопрос" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]"></textarea>
                                        <input v-model="lessonForm.practice_option_a" type="text" placeholder="Вариант A" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                        <input v-model="lessonForm.practice_option_b" type="text" placeholder="Вариант B" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                        <input v-model="lessonForm.practice_option_c" type="text" placeholder="Вариант C" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                        <input v-model="lessonForm.practice_option_d" type="text" placeholder="Вариант D" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]" />
                                        <select v-model="lessonForm.practice_correct_option" class="w-full rounded-2xl border border-[#0D9488] bg-white px-4 py-3 text-[#0F172A] shadow-sm transition focus:border-[#0D9488] focus:outline-none focus:ring-4 focus:ring-[#F8FAFC]">
                                            <option value="a">Правильный ответ: A</option>
                                            <option value="b">Правильный ответ: B</option>
                                            <option value="c">Правильный ответ: C</option>
                                            <option value="d">Правильный ответ: D</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-[#0D9488] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(34,197,94,0.85)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(34,197,94,0.95)]">
                                    Добавить урок
                                </button>
                            </form>
                        </div>

                        <div class="rounded-3xl border border-red-100 bg-white p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#EF4444]">Опасная зона</p>
                            <h2 class="mt-2 text-lg font-semibold text-[#0F172A]">Удаление курса</h2>
                            <p class="mt-3 text-sm leading-7 text-[#1E3A8A]">
                                Удаление курса очистит уроки и записи студентов. Это действие нельзя отменить.
                            </p>
                            <button @click="deleteCourse" class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-[#EF4444] px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_-18px_rgba(220,38,38,0.75)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_40px_-22px_rgba(220,38,38,0.9)]">
                                Удалить курс
                            </button>
                        </div>
                    </aside>
                </div>
            </section>
        </div>

        <AppFooter class="mt-10" />
    </div>
</template>

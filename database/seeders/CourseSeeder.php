<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        LessonProgress::truncate();
        Enrollment::truncate();
        Lesson::truncate();
        Course::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $teacher = User::firstOrCreate(
            ['email' => 'teacher@edu.ru'],
            ['name' => 'Иван Преподавателев', 'password' => Hash::make('password'), 'role' => 'teacher']
        );

        User::firstOrCreate(
            ['email' => 'student@edu.ru'],
            ['name' => 'Мария Студентова', 'password' => Hash::make('password'), 'role' => 'student']
        );

        $course1 = Course::create([
            'title' => 'Основы PHP для начинающих',
            'description' => 'Полный курс по PHP с нуля. Вы научитесь создавать динамические сайты, работать с базами данных MySQL, писать чистый и понятный код.',
            'teacher_id' => $teacher->id,
            'is_published' => true,
            'category' => 'programming',
            'level' => 'beginner',
            'rating' => 4.6,
        ]);

        $course1->lessons()->createMany([
            ['title' => 'Введение в PHP. Установка окружения', 'content' => "В этом уроке мы познакомимся с языком PHP.\n\nPHP — серверный язык программирования. Используется на 77% сайтов в интернете.\n\nЧто понадобится:\n- PHP 8.x\n- Composer\n- VS Code\n\nПроверка установки:\nphp --version", 'order' => 0],
            ['title' => 'Переменные, типы данных и операторы', 'content' => "Типы данных в PHP:\n\n- string: \$name = 'Иван';\n- integer: \$age = 25;\n- float: \$price = 99.99;\n- boolean: \$isActive = true;\n- array: \$colors = ['red', 'green', 'blue'];\n- null: \$nothing = null;\n\nАрифметика: +, -, *, /, %\nСравнение: ==, ===, !=, >, <\nЛогика: &&, ||, !", 'order' => 1],
            ['title' => 'Условия и циклы', 'content' => "Условный оператор:\nif (\$age >= 18) {\n    echo 'Взрослый';\n} else {\n    echo 'Несовершеннолетний';\n}\n\nЦикл for:\nfor (\$i = 0; \$i < 10; \$i++) {\n    echo \$i;\n}\n\nЦикл foreach:\nforeach (\$colors as \$color) {\n    echo \$color;\n}", 'order' => 2],
            ['title' => 'Функции в PHP', 'content' => "Определение функции:\nfunction greet(\$name) {\n    return 'Привет, ' . \$name . '!';\n}\n\nВызов:\necho greet('Иван'); // Привет, Иван!\n\nСтрелочные функции (PHP 7.4+):\n\$double = fn(\$x) => \$x * 2;\necho \$double(5); // 10", 'order' => 3],
            ['title' => 'Работа с массивами', 'content' => "Полезные функции массивов:\n- count(\$arr) — количество элементов\n- array_push(\$arr, \$value) — добавить\n- array_pop(\$arr) — удалить последний\n- array_map(\$fn, \$arr) — применить функцию\n- array_filter(\$arr, \$fn) — отфильтровать\n- in_array(\$value, \$arr) — проверить наличие\n- sort(\$arr) — сортировка", 'order' => 4],
        ]);

        $course2 = Course::create([
            'title' => 'Веб-разработка с Laravel',
            'description' => 'Изучите Laravel — самый популярный PHP-фреймворк. Создайте полноценное веб-приложение: маршруты, контроллеры, модели, шаблоны.',
            'teacher_id' => $teacher->id,
            'is_published' => true,
            'category' => 'frameworks',
            'level' => 'intermediate',
            'rating' => 4.8,
        ]);

        $course2->lessons()->createMany([
            ['title' => 'Что такое Laravel и MVC', 'content' => "Laravel — PHP-фреймворк на архитектуре MVC.\n\nMVC:\n- Model — работа с данными\n- View — шаблоны и HTML\n- Controller — бизнес-логика\n\nПреимущества Laravel:\n- Элегантный синтаксис\n- ORM Eloquent\n- Встроенная аутентификация\n- Огромное сообщество", 'order' => 0],
            ['title' => 'Маршруты и контроллеры', 'content' => "Маршруты в routes/web.php:\n\nRoute::get('/hello', function () {\n    return 'Hello World!';\n});\n\nС контроллером:\nRoute::get('/courses', [CourseController::class, 'index']);\n\nСоздание контроллера:\nphp artisan make:controller CourseController", 'order' => 1],
            ['title' => 'Eloquent ORM и модели', 'content' => "Создание модели:\nphp artisan make:model Course\n\nОперации:\n// Все записи\nCourse::all();\n\n// Найти по ID\nCourse::find(1);\n\n// Создать\nCourse::create(['title' => 'Новый курс']);\n\n// Обновить\n\$course->update(['title' => 'Обновлённый']);\n\n// Удалить\n\$course->delete();", 'order' => 2],
            ['title' => 'Миграции и база данных', 'content' => "Создать миграцию:\nphp artisan make:migration create_courses_table\n\nПример:\nSchema::create('courses', function (Blueprint \$table) {\n    \$table->id();\n    \$table->string('title');\n    \$table->text('description')->nullable();\n    \$table->timestamps();\n});\n\nЗапустить:\nphp artisan migrate", 'order' => 3],
        ]);

        $course3 = Course::create([
            'title' => 'JavaScript для начинающих',
            'description' => 'JavaScript — язык программирования для веба. Работа с DOM, события, AJAX-запросы и интерактивные страницы.',
            'teacher_id' => $teacher->id,
            'is_published' => true,
            'category' => 'programming',
            'level' => 'beginner',
            'rating' => 4.3,
        ]);

        $course3->lessons()->createMany([
            ['title' => 'Введение в JavaScript', 'content' => "JavaScript запускается прямо в браузере.\n\nПодключение:\n<script src=\"app.js\"></script>\n\nВывод в консоль:\nconsole.log('Привет!');\nconsole.error('Ошибка!');\nconsole.warn('Предупреждение!');", 'order' => 0],
            ['title' => 'Работа с DOM', 'content' => "DOM — дерево элементов HTML-страницы.\n\nПолучить элемент:\nconst btn = document.getElementById('myButton');\nconst items = document.querySelectorAll('.item');\n\nИзменить содержимое:\nbtn.textContent = 'Нажми меня';\n\nИзменить стили:\nbtn.style.color = 'red';\nbtn.classList.add('active');", 'order' => 1],
            ['title' => 'События и обработчики', 'content' => "Добавить обработчик:\nbtn.addEventListener('click', () => {\n    alert('Кнопка нажата!');\n});\n\nПопулярные события:\n- click — клик мышью\n- input — ввод в поле\n- submit — отправка формы\n- keydown — нажатие клавиши\n- mouseover — наведение мыши", 'order' => 2],
        ]);

        Course::create([
            'title' => 'Vue.js: современный фронтенд',
            'description' => 'Разработка реактивных интерфейсов на Vue 3. Composition API, компоненты, Pinia, маршрутизация и работа с REST API.',
            'teacher_id' => $teacher->id,
            'is_published' => true,
            'category' => 'frameworks',
            'level' => 'intermediate',
            'rating' => 4.7,
        ])->lessons()->createMany([
            ['title' => 'Введение во Vue 3', 'content' => "Vue — прогрессивный JavaScript-фреймворк.\n\nУстановка:\nnpm create vue@latest\n\nComposition API:\nimport { ref, computed } from 'vue'\nconst count = ref(0)\nconst double = computed(() => count.value * 2)", 'order' => 0],
            ['title' => 'Компоненты и props', 'content' => "Создание компонента:\n<script setup>\nconst props = defineProps({ title: String })\n</script>\n\n<template>\n  <h1>{{ props.title }}</h1>\n</template>", 'order' => 1],
        ]);

        Course::create([
            'title' => 'UI/UX Дизайн для разработчиков',
            'description' => 'Основы дизайна интерфейсов: типографика, цвет, сетка, компоненты. Работа в Figma и создание прототипов.',
            'teacher_id' => $teacher->id,
            'is_published' => true,
            'category' => 'design',
            'level' => 'beginner',
            'rating' => 4.5,
        ])->lessons()->createMany([
            ['title' => 'Основы UI/UX', 'content' => "UI — интерфейс пользователя (кнопки, формы, цвета)\nUX — пользовательский опыт (удобство, логика)\n\nПринципы хорошего дизайна:\n- Простота\n- Последовательность\n- Обратная связь\n- Доступность", 'order' => 0],
            ['title' => 'Работа в Figma', 'content' => "Figma — инструмент для дизайна интерфейсов.\n\nОсновные инструменты:\n- Фреймы (F)\n- Прямоугольники (R)\n- Текст (T)\n- Компоненты (Ctrl+Alt+K)\n\nПрото типирование: соединяй экраны стрелками", 'order' => 1],
        ]);

        Course::create([
            'title' => 'Python Advanced: ООП и паттерны',
            'description' => 'Углублённый курс Python: объектно-ориентированное программирование, паттерны проектирования, декораторы, контекстные менеджеры.',
            'teacher_id' => $teacher->id,
            'is_published' => true,
            'category' => 'programming',
            'level' => 'advanced',
            'rating' => 4.9,
        ])->lessons()->createMany([
            ['title' => 'Классы и наследование', 'content' => "class Animal:\n    def __init__(self, name):\n        self.name = name\n    def speak(self):\n        raise NotImplementedError\n\nclass Dog(Animal):\n    def speak(self):\n        return f'{self.name} говорит: Гав!'", 'order' => 0],
            ['title' => 'Декораторы', 'content' => "def my_decorator(func):\n    def wrapper(*args, **kwargs):\n        print('До вызова')\n        result = func(*args, **kwargs)\n        print('После вызова')\n        return result\n    return wrapper\n\n@my_decorator\ndef hello():\n    print('Привет!')", 'order' => 1],
        ]);
    }
}

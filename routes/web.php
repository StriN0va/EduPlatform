<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/teachers', function () {
    $imageMap = [
        'teacher@edu.ru' => '/images/ddb155cc3231cf94e6da462aa7ed1e92.jpg',
        'shurin@edu.ru' => '/images/MZ5_4022_w.jpg',
    ];

    $teachers = User::query()
        ->where('role', 'teacher')
        ->withCount('courses')
        ->orderBy('name')
        ->get(['id', 'name', 'username', 'email'])
        ->map(function (User $teacher, int $index) use ($imageMap) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'username' => $teacher->username,
                'email' => $teacher->email,
                'courses_count' => $teacher->courses_count,
                'image' => $imageMap[$teacher->email] ?? ($index === 0 ? '/images/delovoj-portret-dlya-andreya.jpg' : null),
            ];
        })
        ->values();

    return Inertia::render('Teachers/Index', [
        'teachers' => $teachers,
    ]);
})->name('teachers.index');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/create', [CourseController::class, 'create'])->middleware('auth')->name('courses.create');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');
    Route::delete('/courses/{course}/enroll', [EnrollmentController::class, 'destroy'])->name('courses.unenroll');
    Route::post('/courses/{course}/rate', [CourseController::class, 'rate'])->name('courses.rate');

    Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/courses/{course}/lessons/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');

    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::patch('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::patch('/courses/{course}/block', [CourseController::class, 'block'])->name('courses.block');

    Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::patch('/courses/{course}/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/courses/{course}/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');

    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::patch('/admin/users/{user}/role', [AdminController::class, 'updateRole'])->name('admin.users.role');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});

require __DIR__.'/auth.php';

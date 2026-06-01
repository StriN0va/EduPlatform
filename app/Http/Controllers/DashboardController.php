<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LessonProgress;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $enrolledCourses = $user->enrolledCourses()->with('lessons', 'teacher')->get()
            ->map(function ($course) use ($user) {
                $totalLessons = $course->lessons->count();
                $completedLessons = LessonProgress::where('user_id', $user->id)
                    ->whereIn('lesson_id', $course->lessons->pluck('id'))
                    ->count();

                return [
                    'id'               => $course->id,
                    'title'            => $course->title,
                    'teacher'          => $course->teacher,
                    'category'         => $course->category,
                    'level'            => $course->level,
                    'total_lessons'    => $totalLessons,
                    'completed_lessons' => $completedLessons,
                    'progress'         => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0,
                    'first_lesson_id'  => $course->lessons->first()?->id,
                ];
            });

        $teacherCourses = $user->isTeacher()
            ? Course::where('teacher_id', $user->id)->withCount('enrollments', 'lessons')->get()
            : collect();

        return Inertia::render('Dashboard', [
            'enrolledCourses' => $enrolledCourses,
            'teacherCourses'  => $teacherCourses,
        ]);
    }
}

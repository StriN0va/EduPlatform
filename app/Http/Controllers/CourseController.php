<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('teacher')->where('is_published', true)->get();
        return Inertia::render('Courses/Index', ['courses' => $courses]);
    }

    public function show(Course $course)
    {
        $course->load(['teacher', 'lessons']);
        $user = auth()->user();
        $canManage = $user && ($user->isAdmin() || $course->teacher_id === $user->id);
        $isEnrolled = auth()->check()
            ? $course->students()->where('user_id', auth()->id())->exists()
            : false;

        return Inertia::render('Courses/Show', [
            'course' => $course,
            'isEnrolled' => $isEnrolled,
            'canManage' => $canManage,
            'canEnroll' => $user && !$user->isAdmin() && $course->teacher_id !== $user->id,
        ]);
    }

    public function create()
    {
        if (!auth()->user()->isTeacher()) {
            abort(403);
        }

        return Inertia::render('Courses/Create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isTeacher()) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|in:programming,frameworks,design',
            'level' => 'nullable|in:beginner,intermediate,advanced',
        ]);

        $course = Course::create([
            ...$data,
            'teacher_id' => auth()->id(),
            'is_published' => true,
        ]);

        return redirect()->route('courses.edit', $course);
    }

    public function edit(Course $course)
    {
        $this->authorizeManageCourse($course);

        $course->load('lessons');
        return Inertia::render('Courses/Edit', ['course' => $course]);
    }

    public function update(Request $request, Course $course)
    {
        $this->authorizeManageCourse($course);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|in:programming,frameworks,design',
            'level' => 'nullable|in:beginner,intermediate,advanced',
        ]);

        $course->update($data);
        return redirect()->route('courses.show', $course);
    }

    public function destroy(Course $course)
    {
        $this->authorizeManageCourse($course);

        $course->delete();
        return redirect()->route('courses.index');
    }

    public function block(Course $course)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $course->update(['is_published' => false]);

        return redirect()->route('courses.index');
    }

    private function authorizeManageCourse(Course $course): void
    {
        $user = auth()->user();

        if (!$user || (!$user->isAdmin() && $course->teacher_id !== $user->id)) {
            abort(403);
        }
    }
}

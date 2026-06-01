<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $isEnrolled = $course->students()->where('user_id', auth()->id())->exists();
        if (!$isEnrolled) {
            return redirect()->route('courses.show', $course);
        }

        $completed = LessonProgress::where('user_id', auth()->id())
            ->where('lesson_id', $lesson->id)->exists();

        $lessons = $course->lessons;
        $completedIds = LessonProgress::where('user_id', auth()->id())
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->pluck('lesson_id');

        return Inertia::render('Lessons/Show', [
            'course' => $course,
            'lesson' => $lesson,
            'lessons' => $lessons,
            'completed' => $completed,
            'completedLessonIds' => $completedIds,
        ]);
    }

    public function complete(Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        LessonProgress::firstOrCreate([
            'user_id' => auth()->id(),
            'lesson_id' => $lesson->id,
        ], ['completed_at' => now()]);

        return back();
    }

    public function store(Request $request, Course $course)
    {
        $this->authorizeManageCourse($course);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'order' => 'integer|min:1',
        ]);

        $data['order'] = max(0, $data['order'] - 1);

        $course->lessons()->create($data);
        return redirect()->route('courses.edit', $course);
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $this->authorizeManageCourse($course);

        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'order' => 'integer|min:1',
        ]);

        $data['order'] = max(0, $data['order'] - 1);

        $lesson->update($data);

        return redirect()->route('courses.edit', $course);
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $this->authorizeManageCourse($course);

        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $lesson->delete();
        return redirect()->route('courses.edit', $course);
    }

    private function authorizeManageCourse(Course $course): void
    {
        $user = auth()->user();

        if (!$user || (!$user->isAdmin() && $course->teacher_id !== $user->id)) {
            abort(403);
        }
    }
}

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

        $lesson->load('practice');

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
            'practice_question' => 'nullable|string',
            'practice_option_a' => 'nullable|string|max:255',
            'practice_option_b' => 'nullable|string|max:255',
            'practice_option_c' => 'nullable|string|max:255',
            'practice_option_d' => 'nullable|string|max:255',
            'practice_correct_option' => 'nullable|in:a,b,c,d',
        ]);

        $data['order'] = max(0, $data['order'] - 1);

        $practiceData = $this->extractPracticeData($data);
        $lesson = $course->lessons()->create($data);

        if ($practiceData) {
            $lesson->practice()->create($practiceData);
        }

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
            'practice_question' => 'nullable|string',
            'practice_option_a' => 'nullable|string|max:255',
            'practice_option_b' => 'nullable|string|max:255',
            'practice_option_c' => 'nullable|string|max:255',
            'practice_option_d' => 'nullable|string|max:255',
            'practice_correct_option' => 'nullable|in:a,b,c,d',
        ]);

        $data['order'] = max(0, $data['order'] - 1);

        $practiceData = $this->extractPracticeData($data);
        $lesson->update($data);

        if ($practiceData) {
            $lesson->practice()->updateOrCreate(['lesson_id' => $lesson->id], $practiceData);
        } else {
            $lesson->practice()->delete();
        }

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

    private function extractPracticeData(array &$data): ?array
    {
        $practice = [
            'question' => $data['practice_question'] ?? null,
            'option_a' => $data['practice_option_a'] ?? null,
            'option_b' => $data['practice_option_b'] ?? null,
            'option_c' => $data['practice_option_c'] ?? null,
            'option_d' => $data['practice_option_d'] ?? null,
            'correct_option' => $data['practice_correct_option'] ?? null,
        ];

        unset(
            $data['practice_question'],
            $data['practice_option_a'],
            $data['practice_option_b'],
            $data['practice_option_c'],
            $data['practice_option_d'],
            $data['practice_correct_option']
        );

        $hasQuestion = filled($practice['question']);
        $hasRequiredAnswers = filled($practice['option_a']) && filled($practice['option_b']) && filled($practice['correct_option']);
        $correctKey = $practice['correct_option'] ? 'option_'.$practice['correct_option'] : null;
        $hasCorrectAnswer = $correctKey && filled($practice[$correctKey]);

        return $hasQuestion && $hasRequiredAnswers && $hasCorrectAnswer ? $practice : null;
    }
}

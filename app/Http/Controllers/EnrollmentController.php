<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Course $course)
    {
        $user = auth()->user();

        if ($user->isAdmin() || $course->teacher_id === $user->id) {
            return redirect()->route('courses.show', $course);
        }

        Enrollment::firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);

        return redirect()->route('courses.show', $course);
    }

    public function destroy(Course $course)
    {
        Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->delete();

        return redirect()->route('courses.show', $course);
    }
}

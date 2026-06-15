<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $search = trim((string) $request->query('q', ''));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $value = ltrim($search, '@');

                $query->where(function ($query) use ($value) {
                    $query->where('username', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'username', 'email', 'role', 'created_at']);

        return Inertia::render('Admin/Users', [
            'users' => $users,
            'filters' => ['q' => $search],
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Нельзя изменить собственную роль.']);
        }

        $request->validate(['role' => 'required|in:student,teacher,admin']);

        $newRole = $request->role;

        // Снимаем роль преподавателя → скрываем курсы
        if ($user->isTeacher() && $newRole === 'student') {
            $user->courses()->update(['is_published' => false]);
        }

        // Возвращаем роль преподавателя → восстанавливаем курсы
        if (!$user->isTeacher() && in_array($newRole, ['teacher', 'admin'])) {
            $user->courses()->update(['is_published' => true]);
        }

        $user->update(['role' => $newRole]);

        return back();
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Нельзя удалить собственный аккаунт через панель администратора.']);
        }

        // Скрыть курсы преподавателя
        if ($user->isTeacher()) {
            $user->courses()->update(['is_published' => false]);
        }

        $user->delete();

        return back();
    }
}

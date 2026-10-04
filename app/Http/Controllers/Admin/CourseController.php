<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Models\Course;
use App\Models\User;
use App\Services\AdminAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::withCount(['users', 'projects']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('department', 'like', "%{$search}%");
        }

        $courses = $query->orderBy('code')->paginate(20)->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function store(StoreCourseRequest $request, AdminAuditService $auditService): RedirectResponse
    {
        $course = Course::create($request->validated());

        /** @var User $admin */
        $admin = Auth::user();
        $auditService->log($admin, 'course.create', $course, ['code' => $course->code, 'name' => $course->name]);

        return back()->with('success', 'Mata kuliah baru berhasil didaftarkan.');
    }

    public function update(UpdateCourseRequest $request, Course $course, AdminAuditService $auditService): RedirectResponse
    {
        $course->update($request->validated());

        /** @var User $admin */
        $admin = Auth::user();
        $auditService->log($admin, 'course.update', $course, ['code' => $course->code, 'name' => $course->name]);

        return back()->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(Course $course, AdminAuditService $auditService): RedirectResponse
    {
        $code = $course->code;
        $name = $course->name;
        $courseId = $course->id;
        $course->delete();

        /** @var User $admin */
        $admin = Auth::user();
        $auditService->log($admin, 'course.delete', null, ['course_id' => $courseId, 'code' => $code, 'name' => $name]);

        return back()->with('success', 'Mata kuliah berhasil dihapus.');
    }
}

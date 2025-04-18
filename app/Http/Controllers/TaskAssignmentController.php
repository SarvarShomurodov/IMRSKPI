<?php

namespace App\Http\Controllers;

use App\Models\TaskAssignment;
use App\Models\Task;
use App\Models\SubTask;
use App\Models\User;
use App\Http\Requests\StoreTaskAssignmentRequest;
use App\Http\Requests\UpdateTaskAssignmentRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TaskAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-taskassign', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-taskassign', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-taskassign', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-taskassign', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the task assignments.
     */
    public function index(): View
    {
        $assignments = TaskAssignment::with(['subtask', 'user'])->latest()->get();
        return view('admin.task_assignments.index', [
            'assignments' => $assignments
        ]);
    }

    /**
     * Show the form for creating a new task assignment.
     */
    public function create(): View
    {
        return view('admin.task_assignments.create', [
            // 'tasks' => Task::all(),
            'subtasks' => SubTask::all(),
            'users' => User::all()
        ]);
    }

    /**
     * Store a newly created task assignment in storage.
     */
    public function store(StoreTaskAssignmentRequest $request): RedirectResponse
    {
        TaskAssignment::create($request->validated());

        return redirect()->route('admin.task_assignments.index')
                         ->withSuccess('Tayinlov muvaffaqiyatli qo‘shildi.');
    }

    /**
     * Show the form for editing the specified task assignment.
     */
    public function edit(TaskAssignment $taskAssignment): View
    {
        return view('admin.task_assignments.edit', [
            'taskAssignment' => $taskAssignment,
            // 'tasks' => Task::all(),
            'subtasks' => SubTask::all(),
            'users' => User::all()
        ]);
    }

    /**
     * Update the specified task assignment in storage.
     */
    public function update(UpdateTaskAssignmentRequest $request, TaskAssignment $taskAssignment): RedirectResponse
    {
        $taskAssignment->update($request->validated());

        return redirect()->route('admin.task_assignments.index')
                         ->withSuccess('Tayinlov muvaffaqiyatli yangilandi.');
    }

    /**
     * Remove the specified task assignment from storage.
     */
    public function destroy(TaskAssignment $taskAssignment): RedirectResponse
    {
        $taskAssignment->delete();

        return redirect()->route('admin.task_assignments.index')
                         ->withSuccess('Tayinlov muvaffaqiyatli o‘chirildi.');
    }
}

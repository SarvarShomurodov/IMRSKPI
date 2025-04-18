<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\SubTask;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\TaskAssignment;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Instantiate a new TaskController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-task', ['only' => ['index','show']]);
        $this->middleware('permission:create-task', ['only' => ['create','store']]);
        $this->middleware('permission:edit-task', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-task', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.tasks.index', [
            'tasks' => Task::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.tasks.create');
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        Task::create($request->all());
        return redirect()->route('admin.tasks.index')
                ->withSuccess('New Task is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(Task $task): View
    // {
    //     return view('admin.tasks.show', [
    //         'task' => $task
    //     ]);
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        return view('admin.tasks.edit', [
            'task' => $task
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->all());
        return redirect()->back()
                ->withSuccess('Task is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')
                ->withSuccess('Task is deleted successfully.');
    }
}

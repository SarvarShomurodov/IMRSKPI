<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use App\Models\Task;
use App\Http\Requests\StoreSubTaskRequest;
use App\Http\Requests\UpdateSubTaskRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SubTaskController extends Controller
{
    /**
     * Instantiate a new SubTaskController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-subtask', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-subtask', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-subtask', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-subtask', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.subtasks.index', [
            'subtasks' => SubTask::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.subtasks.create', [
            'tasks' => Task::all() // Show all tasks for selecting task
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubTaskRequest $request): RedirectResponse
    {
        SubTask::create($request->all());
        return redirect()->route('admin.subtasks.index')
                ->withSuccess('New SubTask is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubTask $subtask): View
    {
        return view('admin.subtasks.show', [
            'subtask' => $subtask
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubTask $subtask): View
    {
        return view('admin.subtasks.edit', [
            'subtask' => $subtask,
            'tasks' => Task::all() // Show all tasks for selecting task
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubTaskRequest $request, SubTask $subtask): RedirectResponse
    {
        $subtask->update($request->all());
        return redirect()->back()
                ->withSuccess('SubTask is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubTask $subtask): RedirectResponse
    {
        $subtask->delete();
        return redirect()->route('admin.subtasks.index')
                ->withSuccess('SubTask is deleted successfully.');
    }
}

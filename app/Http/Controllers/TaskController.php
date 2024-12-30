<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Get tasks and projects
        $projects = Project::all();
        $tasks = Task::query();

        // Filter tasks by project if a project_id is provided
        if ($request->has('project') && $request->project != '') {
            $tasks->where('project_id', $request->project);
        }

        $tasks = $tasks->orderBy('priority')->get();

        return view('tasks.index', compact('tasks', 'projects'));
    }

    // store method to create a new task
    public function store(TaskRequest $request)
    {
        $highestPriority = Task::max('priority') ?? 0;

        Task::create([
            'name' => $request->name,
            'priority' => $highestPriority + 1,
            'project_id' => $request->project_id,
        ]);

        return redirect()->route('tasks.index')
        ->with('success', 'Task added successfully.');
    }


    public function edit($id)
    {
        return view('tasks.edit');
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->only('name', 'project_id'));

        return redirect()->route('tasks.index')
        ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
        ->with('success', 'Task deleted successfully.');
    }

    // Reorder tasks based on drag-and-drop
    public function reorder(Request $request)
    {
        $order = $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|exists:tasks,id',
            'order.*.priority' => 'required|integer',
        ]);

        foreach ($order['order'] as $item) {
            Task::where('id', $item['id'])->update(['priority' => $item['priority']]);
        }

        return response()->json(['message' => 'Tasks reordered successfully.']);
    }

}

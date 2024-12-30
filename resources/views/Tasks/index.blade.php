@extends('layouts.app')

<div class="container">
    <h1>Task Manager</h1>

    <!-- Project Filter -->
    <div class="project-filter">
        <label for="project">Filter by Project:</label>
        <select id="project" onchange="filterTasks()">
            <option value="">All Projects</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Task List -->
    <ul id="task-list" class="task-list">
        @foreach ($tasks as $task)
            <li class="task-item" data-id="{{ $task->id }}">
                    <!-- Task Display -->
                <span class="task-name">{{ $task->name }}</span>
                <span class="task-actions">
                    <button type="button" onclick="startEditTask({{ $task->id }}, '{{ $task->name }}', {{ $task->project_id }})">Edit</button>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </span>
                <!-- Inline Edit Form -->
                <form id="edit-form-{{ $task->id }}" class="edit-task-form" method="POST" action="{{ route('tasks.update', $task) }}" style="display: none;">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $task->name }}" required>
                    <select name="project_id">
                        <option value="">No Project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ $task->project_id == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit">Save</button>
                    <button type="button" onclick="cancelEditTask({{ $task->id }})">Cancel</button>
                </form>
            </li>
        @endforeach
    </ul>

    <!-- Add Task Form -->
    <form method="POST" action="{{ route('tasks.store') }}" class="add-task-form">
        @csrf
        <input type="text" name="name" placeholder="Task Name" required>
        <select name="project_id">
            <option value="">No Project</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>
        <button type="submit">Add Task</button>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
    const taskList = document.getElementById('task-list');
    const sortable = new Sortable(taskList, {
        animation: 150,
        onEnd: function () {
            const order = Array.from(taskList.children).map((item, index) => ({
                id: item.dataset.id,
                priority: index + 1,
            }));

            fetch("{{ route('tasks.reorder') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ order }),
            })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch(error => console.error('Error:', error));
        },
    });

    function filterTasks() {
        const project = document.getElementById('project').value;
        window.location.href = `?project=${project}`;
    }

    function startEditTask(taskId, taskName, projectId) {
        const taskItem = document.querySelector(`.task-item[data-id="${taskId}"]`);
        const taskNameSpan = taskItem.querySelector('.task-name');
        const taskActions = taskItem.querySelector('.task-actions');

        taskNameSpan.style.display = 'none';
        taskActions.style.display = 'none';

        const editForm = document.getElementById(`edit-form-${taskId}`);
        editForm.style.display = 'flex';
    }

    function cancelEditTask(taskId) {
        const taskItem = document.querySelector(`.task-item[data-id="${taskId}"]`);
        const taskNameSpan = taskItem.querySelector('.task-name');
        const taskActions = taskItem.querySelector('.task-actions');

        taskNameSpan.style.display = 'inline';
        taskActions.style.display = 'inline';

        const editForm = document.getElementById(`edit-form-${taskId}`);
        editForm.style.display = 'none';
    }
</script>

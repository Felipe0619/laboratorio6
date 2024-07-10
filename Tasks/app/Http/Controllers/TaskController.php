<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tag;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        $tasks = Task::with('user')->get();
        $users = User::all();
        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function create()
    {
        $tags = Tag::all();
        return view('tasks.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|string',
            'completed' => 'nullable|boolean',
            'tags' => 'nullable|array',
        ]);

        $task = new Task();
        $task->name = $validatedData['name'];
        $task->description = $validatedData['description'];
        $task->priority = $validatedData['priority'];
        $task->completed = $validatedData['completed'];
        $task->user_id = Auth::id();

        $task->save();

        if ($request->has('tags')) {
            $task->tags()->attach($request->tags);
        }

        return redirect('/tasks')->with('success', 'Tarea creada correctamente.');
    }

    public function show(Task $task)
    {
        $task->load('user');
        return view('tasks.show', compact('task'));
      
    }

    public function edit(Task $task)
    {
        $tags = Tag::all();
        return view('tasks.edit', compact('task', 'tags'));
    }

    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|string',
            'completed' => 'nullable|boolean',
            'tags' => 'nullable|array',
        ]);

        $task->name = $validatedData['name'];
        $task->description = $validatedData['description'];
        $task->priority = $validatedData['priority'];
        $task->completed = $validatedData['completed'];

        $task->save();

        if ($request->has('tags')) {
            $task->tags()->sync($request->tags);
        }

        return redirect('/tasks')->with('success', 'Tarea actualizada correctamente.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect('/tasks')->with('success', 'Tarea eliminada correctamente.');
    }

    public function complete(Task $task)
    {
        $task->completed = true;
        $task->save();
        return redirect()->route('tasks.index')->with('success', 'Tarea marcada como completada.');
    }
}

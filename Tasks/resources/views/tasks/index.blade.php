@extends('layouts.app')

@section('title', 'Lista de Tareas')

@section('sidebar')
    @parent
    <p>Bienvenido a la página de tareas.</p>
@endsection

@section('content')
    <div class="container">
        <h1>Tareas</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Crear Tarea</a>
        <a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">Ver Usuarios</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Prioridad</th>
                    <th>Completada</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>
                            <a href="{{ route('tasks.show', $task->id) }}">{{ $task->name }}</a>
                        </td>
                        <td>{{ $task->priority }}</td>
                        <td>{{ $task->completed ? 'Sí' : 'No' }}</td>
                        <td>{{ $task->user->name ?? 'No asignado' }}</td>
                        <td>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary btn-sm">Editar</a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta tarea?')">Eliminar</button>
                            </form>
                            <form action="{{ route('tasks.complete', $task->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Completar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

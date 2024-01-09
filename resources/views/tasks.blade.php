@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                New Task
                </div>
                <div class="card-body">
                    @include('common.info')
                    <form action="{{ route('task.store') }}" method="POST">
                        @csrf
                        <div class="form-group row mt-0 mx-0">
                            <label for="task-name" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">Task</label>
                            <div class="col-md-6">
                                <input type="text" name="name" id="task-name" class="form-control border">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="rounded col-md-3 offset-md-3">
                                <button type="submit" class="btn btn-default border text-nowrap">
                                    <i class="fa fa-btn fa-plus"></i>Add Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @if(isset($tasks) && count($tasks) > 0)
                <div class="card border rounded mb-4">
                    <div class="card-header rounded-top pt-2 pb-2">
                        現在のタスク
                    </div>
                    <div class="card-body pr-3 pl-3">
                        <table class="table table-striped task-table">
                            <thead>
                                <th class="border-top-0 pt-2">Task</th>
                                <th class="border-top-0 pt-2">&nbsp;</th>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        <td class="table-text pt-1 pb-1 text-dark">
                                            <div>{{ $task->name }}</div>
                                        </td>
                                        <td  class="pt-1 pb-1 align-middle">
                                            <form action="{{ route('task.destroy',$task->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger text-nowrap">
                                                    <i class="fa fa-trash"></i>Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
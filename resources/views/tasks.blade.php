@extends('layouts.app')
@section('content')


    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            <div class="card border rounded">
                <div class="card-header pt-2 pb-2">
                New Task
                </div>

                <div class="card-body">
                    {{-- Display Validation Errors --}}
                    @include('common.errors')

                    {{-- New Task Form --}}
                    <form action="{{ url('task') }}" method="POST">
                        {{ csrf_field() }}
                        {{-- Task Name --}}
                        <div class="form-group row mt-0 mx-0">
                            <label for="task-name" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">Task</label>
                            <div class="col-md-6">
                                <input type="text" name="name" id="task-name" class="form-control border">
                            </div>
                        </div>

                        {{-- Add Task Button --}}
                        <div class="form-group">
                            <div class="rounded col-md-3 offset-md-3">
                                <button type="submit" class="btn btn-default border text-nowrap">
                                    <i class="fa fa-btn fa-plus"></i>Add Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- TODO: Current Tasks --}}
                @if(count($tasks) > 0)
                <div class="card border rounded mb-4">
                    <div class="card-header rounded-top pt-2 pb-2">
                        現在のタスク
                    </div>

                    <div class="card-body pr-3 pl-3">
                        <table class="table table-striped task-table">
                            {{-- テーブルヘッダ --}}
                            <thead>
                                <th class="border-top-0 pt-2">Task</th>
                                <th class="border-top-0 pt-2">&nbsp;</th>
                            </thead>

                            {{-- テーブル本体 --}}
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        {{-- タスク名 --}}
                                        <td class="table-text pt-1 pb-1 text-dark">
                                            <div>{{ $task->name }}</div>
                                        </td>
                                        {{-- Delete Button --}}
                                        <td  class="pt-1 pb-1 align-middle">
                                            <form action="{{ url('task/' .$task->id) }}" method="POST">
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
            </div>
            @endif
        </div>
    </div>
@endsection
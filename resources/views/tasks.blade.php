@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                New Task
                </div>

                <div class="panel-body">
                    {{-- Display Validation Errors --}}
                    {{-- resources/views/common/errors.blade.phpをロード --}}
                    @include('common.info')
                    {{-- New Task Form --}}
                    <form action="{{ url('task') }}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                        {{-- Task Name --}}
                        <div class="form-group">
                            <label for="task-name" class="col-sm-3 control-label">Task</label>
                            <div class="col-sm-6">
                                <input type="text" name="name" id="task-name" class="form-control">
                            </div>
                        </div>

                        {{-- Add Task Button --}}
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-btn fa-plus"></i>Add Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- TODO: Current Tasks --}}
                @if(isset($tasks)&&count($tasks) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        現在のタスク
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped task-table">
                            {{-- テーブルヘッダ --}}
                            <thead>
                                <th>Task</th>
                                <th>&nbsp;</th>
                            </thead>

                            {{-- テーブル本体 --}}
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        {{-- タスク名 --}}
                                        <td class="table-text">
                                            <div>{{ $task->name }}</div>
                                        </td>
                                        {{-- Delete Button --}}
                                        <td>
                                            {{-- リクエスト先をtaskのidを入れて指定してる --}}
                                            <form action="{{ url('task/' .$task->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger">
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
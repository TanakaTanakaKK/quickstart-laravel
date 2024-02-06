@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-12 mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                Task List
                </div>
                <div class="card-body col-sm-12">
                    @include('common.info')
                    <form action="{{ route('task.index') }}" method="GET">
                        @csrf
                        <div class="form-group row mt-0 mx-0">
                            <label for="search_word" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">Task</label>
                            <div class="col-md-6">
                                <input type="text" name="search_word" id="search_word" class="form-control border">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="rounded col-md-3 offset-md-6 text-right">
                                <button type="submit" class="btn btn-default border text-nowrap">
                                    <i class="fa-solid fa-magnifying-glass"></i>検索
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                    <div class="card-body px-3 py-0 overflow-auto text-right">
                        <div class="mx-2">
                                    <form action="{{ route('task.index') }}" method="GET">
                                        @if(is_null(session('is_change_for_expired')) || session('is_change_for_expired') == false)
                                        <input type="hidden" name="expired" value="expired">
                                        <button class="btn btn-danger text-nowrap px-3 mx-1">
                                            <i class="fa-solid fa-eye-slash"></i> 期限切れタスクに切替
                                        @else
                                        <input type="hidden" name="active" value="active">
                                        <button class="btn btn-primary text-nowrap px-3 mx-1">
                                            <i class="fa-solid fa-eye"></i> 有効なタスクに切替
                                        @endif
                                        </button>
                                    </form>
                                </div>
                    </div>
                    <div class="card-body px-3 pt-0 overflow-auto">
                        <table class="table table-striped task-table">
                            <thead>
                                @can('isAdmin')
                                <th class="border-top-0 col-2 text-nowrap text-center">画像</th>
                                <th class="border-top-0 col-3 text-nowrap text-center">@sortablelink('name', 'タスク名')</th>
                                <th class="border-top-0 col-2 text-nowrap text-center">@sortablelink('user_id', 'ユーザー')</th>
                                <th class="border-top-0 col-1 text-nowrap text-center">@sortablelink('satus', 'ステータス')</th>
                                <th class="border-top-0 col-3 text-nowrap text-center">@sortablelink('expired_at', '期限')</th>
                                @else
                                <th class="border-top-0 col-1 text-nowrap text-center">画像</th>
                                <th class="border-top-0 col-4 text-nowrap text-center">@sortablelink('name', 'タスク名')</th>
                                <th class="border-top-0 col-1 text-nowrap text-center">@sortablelink('satus', 'ステータス')</th>
                                <th class="border-top-0 col-4 text-nowrap text-center">@sortablelink('expired_at', '期限')</th>
                                @endcan
                                <th class="border-top-0 col-1">&nbsp;</th>
                                <th class="border-top-0 col-1">
                                    <a href="{{ route('task.export_csv') }}" class="btn btn-primary text-nowrap" download>
                                        <i class="fa-solid fa-file-export"></i>出力
                                    </a>
                                </th>
                                <th class="border-top-0 col-1">
                                    <a href="{{ route('task.create') }}" class="btn btn-primary text-nowrap">
                                        <i class="fa-solid fa-plus"></i>作成
                                    </a>
                                </th>
                            </thead>
                            <tbody>
                                @if($tasks->isEmpty())
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                        @can('isAdmin')
                                        <td>&nbsp;</td>
                                        @endcan
                                        <td colspan="5">&nbsp;</td>
                                    </tr>
                                @else
                                @foreach($tasks as $task)
                                    <tr>
                                        <td class="table-text py-0 align-middle text-dark text-center">
                                            <div class="image-field">
                                                <img src="{{ asset('/storage/task/thumbnail_images/'.$task->thumbnail_image_path) }}" class="w-50"
                                                onerror="this.onerror=null;this.src='{{ asset('/storage/task/thumbnail_images/default.webp') }}';">
                                            </div>
                                        </td>
                                        <td class="table-text py-0 align-middle text-dark text-center">
                                            <div>{{ $task->name }}</div>
                                        </td>
                                        @can('isAdmin')
                                        <td class="table-text py-0 align-middle text-dark text-center">
                                            <div>{{ $task->user->name }}</div>
                                        </td>
                                        @endcan
                                        <td class="table-text py-0 align-middle text-dark text-nowrap text-center">
                                            <div>{{ \App\Enums\TaskStatus::getDescription($task->status) }}</div>
                                        </td>
                                        <td class="table-text py-0 align-middle text-dark text-nowrap text-center">
                                            @if(is_null(session('is_change_for_expired')) || session('is_change_for_expired') == false)
                                            <div>{{ \Carbon\Carbon::parse($task->expired_at)->format('Y年m月d日 H:i') }}</div>
                                            @else
                                            <div class="text-danger">{{ \Carbon\Carbon::parse($task->expired_at)->format('Y年m月d日 H:i') }}</div>
                                            @endif
                                        </td>
                                        <td class="py-0 align-middle text-center">
                                            <a href="{{ route('task.show',$task->id) }}" class="btn btn-primary text-nowrap">
                                                <i class="fa-solid fa-circle-info"></i>詳細
                                            </a>
                                        </td>
                                        <td class="py-0 align-middle text-center">
                                            <a href="{{ route('task.edit',$task->id) }}" class="btn btn-primary text-nowrap">
                                                <i class="fa-regular fa-pen-to-square"></i>編集
                                            </a>
                                        </td>
                                        <td class="align-middle text-center">
                                            <button type="submit" class="btn btn-danger text-nowrap delete_button" data-name="{{ $task->name }}" value="{{ $task->id }}">
                                                <i class="fa fa-trash"></i>削除
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="container-fluid">
                            <div class="row justify-content-md-center">
                                <div class="align-center col">
                                    &nbsp;
                                </div>
                                <div class="align-center col">
                                    {{ $tasks->links('pagination::bootstrap-4') }}
                                </div>
                                <div class="align-center col">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
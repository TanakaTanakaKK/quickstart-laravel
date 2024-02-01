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
                <div class="card border rounded">
                    <div class="card-body pr-3 pl-3 overflow-auto">
                        <table class="table table-striped task-table">
                            <thead>
                                @if(request()->user_status === \App\Enums\UserStatus::ADMIN)
                                <th class="border-top-0 col-2 text-nowrap">画像</th>
                                <th class="border-top-0 col-3 text-nowrap">タスク名</th>
                                <th class="border-top-0 col-2 text-nowrap">ユーザー名</th>
                                <th class="border-top-0 col-3 text-nowrap">期限</th>
                                <th class="border-top-0 col-1">&nbsp;</th>
                                <th class="border-top-0 col-1">&nbsp;</th>
                                @else
                                <th class="border-top-0 col-2 text-nowrap">画像</th>
                                <th class="border-top-0 col-4 text-nowrap">タスク名</th>
                                <th class="border-top-0 col-4 text-nowrap">期限</th>
                                <th class="border-top-0 col-1">&nbsp;</th>
                                <th class="border-top-0 col-1">&nbsp;</th>
                                @endif
                                <th class="border-top-0 col-1">
                                    <a href="{{ route('task.create') }}" class="btn btn-primary text-nowrap">
                                        <i class="fa-solid fa-plus"></i>作成
                                    </a>
                                </th>
                            </thead>
                            <tbody>
                                @if(count($tasks) === 0)
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        @if(request()->user_status === \App\Enums\UserStatus::ADMIN)
                                        <td>&nbsp;</td>
                                        @endif
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                @else
                                @foreach($tasks as $task)
                                    <tr>
                                        <td class="table-text py-0 align-middle text-dark">
                                            <div class="image-field">
                                                <img src="{{ asset('/storage/task/thumbnail_images/'.$task->thumbnail_image_path) }}" class="w-50">
                                            </div>
                                        </td>
                                        <td class="table-text py-0 align-middle text-dark">
                                            <div>{{ $task->name }}</div>
                                        </td>
                                        @if(request()->user_status === \App\Enums\UserStatus::ADMIN)
                                        <td class="table-text py-0 align-middle text-dark">
                                            <div>{{ $task->user->name }}</div>
                                        </td>
                                        @endif
                                        <td class="table-text py-0 align-middle text-dark text-nowrap">
                                            <div>{{ \Carbon\Carbon::parse($task->expired_at)->format('Y年m月d日 H:i') }}</div>
                                        </td>
                                        <td class="py-0 align-middle">
                                            <a href="{{ route('task.show',$task->id) }}" class="btn btn-primary text-nowrap">
                                                <i class="fa-solid fa-circle-info"></i>詳細
                                            </a>
                                        </td>
                                        <td class="py-0 align-middle">
                                            <a href="{{ route('task.edit',$task->id) }}" class="btn btn-primary text-nowrap">
                                                <i class="fa-regular fa-pen-to-square"></i>編集
                                            </a>
                                        </td>
                                        <td class="align-middle">
                                            <button type="submit" class="btn btn-danger text-nowrap delete_button" data-name="{{ $task->name }}" value="{{ $task->id }}">
                                                <i class="fa fa-trash"></i>削除
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="{{ asset('/functions.js') }}" defer></script>
@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="col-sm-offset-2 col-sm-8 mx-auto">
        <div class="card border rounded">
            <div class="card-header py-2">
            タスク詳細画面
            </div>
            <div class="card-body">
                @include('common.info')
                <div class="form-group row my-1 mx-0">
                    <label for="image_file" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">画像</label>
                    <div class="col-md-6">
                        <img src="{{ asset('/storage/task/thumbnail_images/'.$task->thumbnail_image_path) }}" class="w-50 border">
                    </div>
                </div>
                <div class="form-group row my-2 mx-0">
                    <label for="name" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">タスク名</label>
                    <div class="col-md-6">
                        <p class="form-control mb-0">{{ $task->name }}</p>
                    </div>
                </div>
                <div class="form-group row my-2 mx-0">
                    <label for="detail" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">内容</label>
                    <div class="col-md-6">
                        <div class="border rounded pl-2">
                            <p class="my-1 mx-1">{{ $task->detail }}</p>
                        </div>
                    </div>
                </div>
                <div class="form-group row my-2 mx-0">
                    <label for="expired_at" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">期限</label>
                    <div class="col-md-6">
                        <p class="form-control mb-0">{{ $task->expired_at }}</p>
                    </div>
                </div>
                <div class="form-group row my-2 mx-0">
                    <label for="status" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">ステータス</label>
                    <div class="col-md-6">
                        <p class="form-control">{{ \App\Enums\TaskStatus::getDescription($task->status) }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="rounded col-md-6 offset-md-3 text-right">
                        <a href="{{ route('task.index') }}" class="col-3 btn btn-primary text-nowrap">
                            <i class="fa-solid fa-reply-all"></i>一覧
                        </a>
                        <a href="{{ route('task.edit', $task->id) }}" class="col-3 btn btn-primary text-nowrap">
                            <i class="fa-regular fa-pen-to-square"></i>編集
                        </a>
                    </div>
                </div>
            </div>
            <br>
            <div class="card-header py-2">
                コメント欄
            </div>
            <div class="card_body">
                @if(isset($comments))
                @foreach($comments as $comment)
                <div class="align-middle form-group row my-2 mx-0">
                    <label for="detail" class="col-md-3 text-md-right text-sm-left col-form-label">
                        {{ $comment->user->name }}
                        <br><small>{{ $comment->created_at }}</small>
                    </label>
                    <div class="align-middle col-md-6 py-2">
                        <div class="border rounded pl-2">
                            <p class="my-1 mx-1">{{ $comment->comment }}</p>
                        </div>
                    </div>
                    @if($comment->user->status === \App\Enums\UserStatus::ADMIN && $comment->user->id !== auth()->id())
                    <div class="text-center align-middle form-group row my-2 mx-0">
                        <input type="hidden" name="user_id" value="{{ $task->user_id }}">
                        <div class="col-md-3">
                            <div class="btn btn-secondary boder text-nowrap">
                                <i class="fa fa-trash"></i>削除
                            </div>
                        </div>
                    </div>
                    @else
                    <div>
                        <form action="{{ route('task_comment.destroy', $comment->id) }}" class="align-middle form-group row my-2 mx-0" method="POST">
                        @csrf
                        @method('DELETE')
                            <div class="text-center col-md-3">
                                <input type="hidden" name="user_id" value="{{ $task->user_id }}">
                                <button type="submit" class="btn btn-danger boder text-nowrap">
                                    <i class="fa fa-trash"></i>削除
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif
            </div>
            <div class="card_body">
                <form action="{{ route('task_comment.store') }}" class="align-middle form-group row my-2 mx-0" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $task->user_id }}">
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <label for="comment" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">コメント</label>
                    <div class="col-md-6">
                        <textarea name="comment" id="comment" class="form-control border" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="py-auto align-middle text-center col-md-3">
                            <button type="submit" class="btn btn-primary boder text-nowrap">
                                <i class="fa-solid fa-upload"></i>投稿
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
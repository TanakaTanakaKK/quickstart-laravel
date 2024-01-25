<script src="{{ asset('/functions.js') }}" defer></script>
@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="col-sm-offset-2 col-sm-8 mx-auto">
        <div class="card border rounded">
            <div class="card-header py-2">
            {{ $task->name }}
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
                    <label for="detail" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">詳細</label>
                    <div class="col-md-6">
                        <div class="border rounded">
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
        </div>
    </div>
</div>
@endsection
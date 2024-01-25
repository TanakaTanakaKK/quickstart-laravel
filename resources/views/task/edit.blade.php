<script src="{{ asset('/functions.js') }}" defer></script>
@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                タスク編集画面
                </div>
                <div class="card-body">
                    @include('common.info')
                    <form action="{{ route('task.update', $task->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group row my-1 mx-0">
                            <label for="image_file" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">画像</label>
                            <div class="input-group col-md-6">
                                <span class="input-group-btn w-100">
                                    <label for="image_file" class="btn btn-default border  form-control text-left">
                                        ファイルを選択<input type="file" name="image_file" class="d-none" id="image_file" onchange=showFileName()
                                        accept="image/png, image/jpeg, image/gif, image/webp">
                                    </label>
                                    <p id='image_info'></p>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row mt-0 mx-0">
                            <label for="name" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">タスク名</label>
                            <div class="col-md-6">
                                <input type="text" name="name" id="name" class="form-control border" value="{{ $task->name }}">
                            </div>
                        </div>
                        <div class="form-group row mt-0 mx-0">
                            <label for="detail" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">詳細</label>
                            <div class="col-md-6">
                                <textarea name="detail" id="detail" class="form-control border" rows="2">{{ $task->detail }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mt-0 mx-0">
                            <label for="expired_at" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">期限</label>
                            <div class="col-md-6">
                                <input type="datetime-local" name="expired_at" id="expired_at" class="form-control border" value="{{ $task->expired_at }}">
                            </div>
                        </div>
                        <div class="form-group row mt-0 mx-0">
                            <label for="status" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">ステータス</label>
                            <div class="col-md-6">
                                <select name="status" id="status" class="form-control border">
                                    <option value="" selected hidden>{{ \App\Enums\TaskStatus::getDescription($task->status) }}</option>
                                    @foreach(\App\Enums\TaskStatus::asSelectArray() as $key => $task_status)
                                            <option value="{{ $key }}">{{ $task_status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="rounded col-md-6 offset-md-3 text-right">
                                <button type="submit" class="btn btn-primary border text-nowrap">
                                    <i class="fa-solid fa-upload"></i> 更新
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
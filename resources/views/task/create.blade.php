<script src="{{ asset('/functions.js') }}" defer></script>
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
                    <form action="{{ route('task.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row my-1 mx-0">
                            <label for="image_file" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">画像
                                <span class="badge text-danger">*</span>                                        
                            </label>
                            <div class="input-group col-md-6">
                                <span class="input-group-btn w-100">
                                    <label for="image_file" class="btn btn-default border text-left form-control">
                                        画像ファイルを選択してください<input type="file" name="image_file" class="d-none" id="image_file" onchange=showImageFileName()
                                        accept="image/png, image/jpeg, image/gif, image/webp">
                                    </label>
                                    <p id='image_info'></p>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row mt-0 mx-0">
                            <label for="name" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">タスク名
                                <span class="badge text-danger">*</span>   
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="name" id="name" class="form-control border">
                            </div>
                        </div>
                        <div class="form-group row mt-0 mx-0">
                            <label for="detail" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">内容
                                <span class="badge text-danger">*</span>   
                            </label>
                            <div class="col-md-6">
                                <textarea name="detail" id="detail" class="form-control border" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group row mt-0 mx-0">
                            <label for="expired_at" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">期限
                                <span class="badge text-danger">*</span>   
                            </label>
                            <div class="col-md-6">
                                <input type="datetime-local" name="expired_at" id="expired_at" class="form-control border">
                            </div>
                        </div>
                        <div class="form-group row mt-0 mx-0">
                            <label for="status" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">ステータス
                                <span class="badge text-danger">*</span>   
                            </label>
                            <div class="col-md-6">
                                <select name="status" id="status" class="form-control border">
                                    <option value="" selected hidden>選択してください</option>
                                    @foreach(\App\Enums\TaskStatus::asSelectArray() as $key => $task_status)
                                        @if($task_status !== '終了')
                                            <option value="{{ $key }}">{{ $task_status }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="rounded col-md-6 offset-md-3 text-right ">
                                <button type="submit" class="btn btn-default border text-nowrap">
                                    <i class="fa fa-btn fa-plus"></i>Add Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-header py-2">
                    CSVからタスクを登録
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <form action="{{ route('task.store_csv') }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="form-group row my-1 mx-0" novalidate>
                                <label for="csv_file" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">CSVファイル</label>
                                <div class="input-group col-md-6">
                                    <span class="input-group-btn w-100">
                                        <label for="csv_file" class="btn btn-default border text-left form-control">
                                            <p id="csv_name">CSVファイルを選択してください</p><input type="file" name="csv_file" class="d-none" id="csv_file"accept="text/csv"
                                            onchange=showCsvFileName()>
                                        </label>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="rounded col-md-6 offset-md-3 text-right ">
                                    <button type="submit" class="btn btn-default border text-nowrap">
                                        <i class="fa fa-btn fa-plus"></i>Add Task
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
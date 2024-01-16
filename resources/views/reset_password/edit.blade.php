@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                    パスワードリセット
                </div>
                <div class="card-body">
                    <form action="{{ route('reset_password.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="reset_password_token" value="{{ request()->reset_password_token }}">
                        <div class="mt-0 mx-0">
                            <div class="panel-body">
                                @include('common.info')
                                <label for="password" class="col-form-label font-weight-bold">新しいパスワード</label>
                                <div class="form-group col-md-12 px-0">
                                    <input type="password" name="password" class="form-control" id="password">
                                </div>
                                <label for="password_confirmation" class="col-form-label font-weight-bold">新しいパスワード(確認用)</label>
                                <div class="form-group col-md-12 px-0">
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                                </div>
                                <div class="form-group">
                                    <div class="rounded text-right">
                                        <button class="btn border">
                                            <i></i>設定
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>  
        </div>
    </div>
@endsection
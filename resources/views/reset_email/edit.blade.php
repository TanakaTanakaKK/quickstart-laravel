@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                    メールアドレスの更新
                </div>
                <div class="card-body">
                    <form action="{{ route('reset_email.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="reset_password_token" value="{{ request()->reset_email_token }}">
                        <div class="mt-0 mx-0">
                            <div class="panel-body">
                                @include('common.info')
                                <label for="password" class="col-form-label font-weight-bold">メールアドレス</label>
                                <div class="form-group col-md-12 px-0">
                                    <input type="text" class="form-control">
                                </div>
                                <label for="password_confirmation" class="col-form-label font-weight-bold">新しいメールアドレス</label>
                                <div class="form-group col-md-12 px-0">
                                    <p class="form-control, border, overflow-auto"></p>
                                </div>
                                <div class="form-group">
                                    <div class="rounded text-right">
                                        <button class="btn border">
                                            <i class="fa-solid fa-user-plus"></i> 更新
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
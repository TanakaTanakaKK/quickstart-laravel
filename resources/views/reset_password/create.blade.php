@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                    パスワードリセット
                </div>
                <div class="card-body">
                    <form action="{{ route('reset_password.store') }}" method="POST">
                        @csrf
                        <div class="mt-0 mx-0">
                            <div class="panel-body">
                                @include('common.info')
                                @empty($email)
                                <label for="email-form" class="col-form-label font-weight-bold">Eメールアドレス</label>
                                <div class="form-group col-md-12 px-0">
                                    <input type="text" name="email" class="form-control" id="email-form">
                                </div>
                                <div class="form-group">
                                    <div class="rounded text-right">
                                        <button class="btn btn-default border text-nowrap">
                                            <i class="fa-regular fa-paper-plane"></i>送信
                                        </button>
                                    </div>
                                </div>
                                @endempty
                            </div>
                        </div>
                    </form>
                </div>
            </div>  
        </div>
    </div>
@endsection
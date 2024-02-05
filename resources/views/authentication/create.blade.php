@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                    @if((int)$authentication_type === \App\Enums\AuthenticationType::USER_REGISTER)
                    登録用メールアドレス
                    @elseif((int)$authentication_type === \App\Enums\AuthenticationType::PASSWORD_RESET)
                    パスワードリセット
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ route('authentications.store') }}" method="POST">
                        @csrf
                        <div class="mt-0 mx-0">
                            <div class="panel-body">
                                @include('common.info')
                                <input type="hidden" name="authentication_type" value="{{ $authentication_type }}">
                                @if((int)$authentication_type === \App\Enums\AuthenticationType::USER_REGISTER)
                                <label for="email-form" class="col-form-label font-weight-bold">Eメールアドレス</label>
                                @elseif((int)$authentication_type === \App\Enums\AuthenticationType::PASSWORD_RESET)
                                <label for="email-form" class="col-form-label font-weight-bold">登録しているEメールアドレス</label>
                                @endif
                                <div class="form-group col-md-12 px-0">
                                    <input type="text" name="email" class="form-control" id="email-form">
                                </div>
                                <div class="form-group">
                                    <div class="rounded text-right">
                                        <button class="btn border">
                                            <i class="fa-regular fa-paper-plane"></i> 送信
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
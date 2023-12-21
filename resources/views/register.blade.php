@extends('layouts.app')
@section('content')
    <form action="{{ url("register") }}" method="POST">
        @csrf
        <div class="container">
            <div class="panel panel-default px-4">
                <div class="panel-heading">
                    登録用メールアドレス
                </div>
                <div class="panel-body">
                    <div class="form-group">
                    @include('common.errors')
                    @empty($email)
                        <label for="email-form">Eメールアドレス</label>
                        <div>
                            <input type="text" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="">
                            <button>
                                <i></i>送信
                            </button>
                        </div>
                    </div>
                    @endempty
                    @isset($email)
                    <p>{{$email}}宛にメールを送信しました。</p>
                    @endisset
                </div>
            </div>
        </div>
    
    </form>    



@endsection
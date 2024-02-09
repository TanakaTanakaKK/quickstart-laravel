@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                    メールアドレスの更新
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update_email', $user_id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mt-0 mx-0">
                            <div class="panel-body">
                                @include('common.info')
                                <input type="hidden" name="email" value="{{ $user_email }}">
                                <label for="after_email" class="col-form-label font-weight-bold">更新後メールアドレス</label>
                                <div class="form-group col-md-12 px-0">
                                    <p class="form-control border overflow-auto">{{ $user_email }}</p>
                                </div>
                                <div class="form-group">
                                    <div class="rounded text-right">
                                        <button class="btn border">
                                            <i class="fa-solid fa-upload"></i>  確定
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
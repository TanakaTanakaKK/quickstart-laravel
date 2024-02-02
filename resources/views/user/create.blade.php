<script src="{{ asset('/functions.js') }}" defer></script>
@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                    会員登録ページ
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="authentication_token" value="{{ request()->authentication_token }}">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                @include('common.info')
                                <div class="form-group">
                                    <label for="image_file" class="control-label">画像
                                        <span class="badge text-danger">*</span>                                        
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <label class="btn btn-default border">
                                                ファイルを選択<input type="file" name="image_file" class="d-none" id="image_file" onchange=showImageFileName()
                                                accept="image/png, image/jpeg, image/gif, image/webp">
                                            </label>
                                        </span>
                                    </div>
                                    <p id='image_info'></p>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="control-label">氏名
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" class="form-control border" id="name">
                                </div>
                                <div class="form-group">
                                    <label for="kana_name" class="control-label">氏名(カナ)
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="text" name="kana_name" class="form-control border" id="kana_name">
                                </div>
                                <div class="form-group">
                                    <label for="nickname" class="control-label">ニックネーム
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="text" name="nickname" class="form-control border" id="nickname">
                                </div>
                                <div class="form-group">
                                    <label for="gender" class="control-label">性別
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <select name="gender" class="form-control border" id="gender">
                                        <option value="" selected hidden>選択してください</option>
                                        @foreach(\App\Enums\Gender::asSelectArray() as $key => $gender)
                                            <option value="{{ $key }}">{{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="birthday" class="control-label">生年月日
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="date" name="birthday" class="form-control border" id="birthday">
                                </div>
                                <div class="form-group">
                                    <label for="phonenumber" class="control-label">電話番号
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="text" name="phone_number" class="form-control border" id="phonenumber">
                                </div>
                                <div class="form-group">
                                    <label for="postal_code" class="control-label">郵便番号
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="tel" name="postal_code" class="form-control border" id="postal_code" oninput="searchPostal()">
                                </div>
                                <div class="form-group">
                                    <label for="prefecture" class="control-label">都道府県
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <select name="prefecture" class="form-control border" id="prefecture">
                                        <option selected hidden>選択してください</option>
                                        @foreach(\App\Enums\Prefecture::asSelectArray() as $key => $prefecture)
                                            <option value="{{ $key }}">{{ $prefecture }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="control-label">市区町村
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="text" name="address" class="form-control border" id="address">
                                </div>
                                <div class="form-group">
                                    <label for="block" class="control-label">番地
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="text" name="block" class="form-control border" id="block">
                                </div>
                                <div class="form-group">
                                    <label for="building" class="control-label">建物</label>
                                    <input type="text" name="building" class="form-control border" id="building">
                                </div>
                                <div class="form-group">
                                    <label for="password" class="control-label">パスワード
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="password" name="password" class="form-control border" id="password">
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation" class="control-label">パスワード(確認用)
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" class="form-control border" id="password_confirmation">
                                </div>
                                <div class="form-group">
                                    <div class="text-right">
                                        <button type="submit" class="btn border">
                                            <i class="fa-solid fa-user-plus"></i> 会員登録
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
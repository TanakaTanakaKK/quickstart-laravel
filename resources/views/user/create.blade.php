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
                        <input type="hidden" name="user_token" value="{{ request()->token }}">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                @include('common.info')
                                <div class="form-group">
                                    <label for="user-img" class="control-label">画像</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <label class="btn btn-default border">
                                                ファイルを選択<input type="file" name="user_img" style="display: none;">
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="control-label">氏名
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" class="form-control border">
                                </div>
                                <div class="form-group">
                                    <label for="kana-name" class="control-label">氏名(カナ)
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="text" name="kana_name" class="form-control border">
                                </div>
                                <div class="form-group">
                                    <label for="nickname" class="control-label">ニックネーム
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="text" name="nickname" class="form-control border">
                                </div>
                                <div class="form-group">
                                    <label for="gender" class="control-label">性別
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <select name="gender" class="form-control border">
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
                                    <input type="date" name="birthday" class="form-control border">
                                </div>
                                <div class="form-group">
                                    <label for="phonenumber" class="control-label">電話番号
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="text" name="phone_number" class="form-control border">
                                </div>
                                <div class="form-group">
                                    <label for="postalcode" class="control-label">郵便番号
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="tel" name="postalcode" class="form-control border" id="postalcode" oninput="searchPostal()">
                                </div>
                                <div class="form-group">
                                    <label for="prefecture" class="control-label">都道府県
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <select name="prefecture" class="form-control border" id="prefecture">
                                        <option selected hidden>選択してください</option>
                                        @foreach(\App\Enums\Prefectures::asSelectArray() as $key => $prefectures)
                                            <option value="{{ ($key) }}">{{ $prefectures }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="city" class="control-label">市区町村
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="text" name="cities" class="form-control border" id="cities">
                                </div>
                                <div class="form-group">
                                    <label for="block" class="control-label">番地
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="text" name="block" class="form-control border">
                                </div>
                                <div class="form-group">
                                    <label for="building" class="control-label">建物</label>
                                    <input type="text" name="building" class="form-control border">
                                </div>
                                <div class="form-group">
                                    <label for="password" class="control-label">パスワード
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="password" name="password" class="form-control border">
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation" class="control-label">パスワード(確認用)
                                        <span class="badge text-danger">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" class="form-control border">
                                </div>
                                <div class="form-group">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-default border text-nowrap">
                                            <i class="fa fa-btn fa-plus"></i>会員登録
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
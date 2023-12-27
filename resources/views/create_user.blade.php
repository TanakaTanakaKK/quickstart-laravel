<script src="{{ asset('/functions.js') }}" defer></script>
@extends('layouts.app')
@section('content')
    <form action="{{ url("create_user") }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="col-sm-offset-2 col-sm-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        会員登録ページ
                    </div>
                    <div class="panel-body">
                        @include('common.info')
                        <div class="form-group">
                            <label for="user-img" class="col-sm-4 control-label">画像</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <label class="btn btn-default">
                                            ファイルを選択<input type="file" name="user_img" style="display: none;">
                                        </label>
                                    </span>
                                    <input type="text" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">氏名</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kana-name" class="col-sm-4 control-label">氏名(カナ)</label>
                            <div class="col-sm-8">
                                <input type="text" name="kana_name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nickname" class="col-sm-4 control-label">ニックネーム</label>
                            <div class="col-sm-8">
                                <input type="text" name="nickname" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="col-sm-4 control-label">性別</label>
                            <div class="col-sm-8">
                                <select name="gender" class="form-control">
                                    <option value="" selected hidden>選択してください</option>
                                    @foreach(\App\Enums\Gender::getValues() as $gender)
                                        <option value="{{ $gender }}">{{$gender}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="birthday" class="col-sm-4 control-label">生年月日</label>
                            <div class="col-sm-8">
                                <input type="date" name="birthday" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phonenumber" class="col-sm-4 control-label">電話番号</label>
                            <div class="col-sm-8">
                                <input type="text" name="phone_number" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="postalcode" class="col-sm-4 control-label">郵便番号</label>
                            <div class="col-sm-8">
                                <input type="tel" name="postalcode" class="form-control" id="postalcode" oninput="searchPostal()">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="prefecture" class="col-sm-4 control-label">都道府県</label>
                            <div class="col-sm-8">
                                <select name="prefecture" class="form-control" id="prefecture" size='1'>
                                    <option value="" selected hidden>選択してください</option>
                                    @foreach(\App\Enums\Prefectures::getValues() as $prefecture)
                                        <option value="{{ $prefecture }}">{{$prefecture}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="col-sm-4 control-label">市</label>
                            <div class="col-sm-8">
                                <input type="text" name="city" class="form-control" id="city">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="town" class="col-sm-4 control-label">区町村</label>
                            <div class="col-sm-8">
                                <input type="text" name="town" class="form-control" id="town">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="block" class="col-sm-4 control-label">番地</label>
                            <div class="col-sm-8">
                                <input type="text" name="block" class="form-control" id="block">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="building" class="col-sm-4 control-label">建物</label>
                            <div class="col-sm-8">
                                <input type="text" name="building" class="form-control" id="building">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-6 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-btn fa-plus"></i>会員登録
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
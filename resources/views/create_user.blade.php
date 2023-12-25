@extends('layouts.app')
<script src="{{ asset('/functions.js') }}" defer></script>
@section('content')
    <form action="{{ url("create_user") }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="panel panel-default px-4">
                <div class="panel-heading">
                    会員登録ページ
                </div>
                <div class="panel-body">
                    @include('common.errors')
                    <div class="input-group col-sm-6">
                        <label for="user-img" class="input-group-btn">画像</label>
                        <div class="btn">
                            <input type="file" name="user_img" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="name">氏名</label>
                        <div>
                            <input type="text" name="name" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="kana-name">氏名(カナ)</label>
                        <div>
                            <input type="text" name="kana_name" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="nickname">ニックネーム</label>
                        <div>
                            <input type="text" name="nickname" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-inline col-sm-3">
                        <label for="gender control-lavel">性別</label>
                        <div class="radio-inline">
                            <input type="radio" name="gender" value="男">男
                        </div>
                        <div class="radio-inline">
                            <input type="radio" name="gender" value="女">女
                        </div>
                    </div>
                    <div class="form-group form-inline col-sm-3">
                        <label type="birthday">生年月日</label>
                        <div>
                            <input type="date" name="birthday">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="phone-number">電話番号</label>
                        <div>
                            <input type="tel" name="phone_number" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="postal-code">郵便番号</label>
                        <div>
                            <input type="tel" name="postalcode" class="form-control" id="postalcode" oninput="searchPostal()" >
                        </div>
                    </div>                    
                    <div class="form-group col-sm-6">
                        <label for="prefecture">都道府県</label>
                        <div>
                            <input type="text" name="prefecture" class="form-control" id="prefecture">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="city">市</label>
                        <div>
                            <input type="text" name="city" class="form-control" id="city">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="town">区町村</label>
                        <div>
                            <input type="text" name="town" class="form-control" id="town">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="block">番地</label>
                        <div>
                            <input type="text" name="block" class="form-control" id="block" >
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="building">建物</label>
                        <div>
                            <input type="text" name="building" class="form-control" id="building" >
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-default">
                            <i class="fa fa-btn fa-plus"></i>会員登録
                        </button>

                    </div>
                </div>
   
            </div>
        </div>
    
    </form>
    
@endsection
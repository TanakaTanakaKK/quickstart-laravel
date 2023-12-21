@extends('layouts.app')
<script src="{{ asset('/functions.js') }}" defer></script>
@section('content')
    <form action="{{ url("createuser") }}" method="POST">
        @csrf
        <div class="container">
            <div class="panel panel-default px-4">
                <div class="panel-heading">
                    会員登録ページ
                </div>
                <div class="panel-body">
                    <div class="form-group col-sm-6">
                        <label for="name">氏名</label>
                        <div>
                            <input type="text" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="kana-name">カナ</label>
                        <div>
                            <input type="text" name="kana-name" class="form-control">
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
                        <label type="barthday">生年月日</label>
                        <div>
                            <input type="date" name="barthday" value="">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="tel">電話番号</label>
                        <div>
                            <input type="text" name="tel" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="postal-code">郵便番号</label>
                        <div>
                            <input type="text" name="postalcode" class="form-control" id="postalcode" oninput="searchPostal()">
                        </div>
                    </div>                    
                    <div class="form-group col-sm-6">
                        <label for="prefecture">都道府県</label>
                        <div>
                            <input type="text" name="prefecture" class="form-control" value="" id="prefecture">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="city">市区町村</label>
                        <div>
                            <input type="text" name="city" class="form-control" value="" id="city">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="block">住所</label>
                        <div>
                            <input type="text" name="block" class="form-control" value="" id="block">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="building">建物</label>
                        <div>
                            <input type="text" name="building" class="form-control" value="" id="building">
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
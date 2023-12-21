@extends('layouts.app')
@section('content')
    <form action="{{ url("register") }}" method="POST">
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
                            <input type="text" name="name" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="kana-name">カナ</label>
                        <div>
                            <input type="text" name="kana-name" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="nickname">ニックネーム</label>
                        <div>
                            <input type="text" name="nickname" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="gender">性別</label>
                        <div>
                            <input type="text" name="gender" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="barthday">生年月日</label>
                        <div>
                            <input type="text" name="barthday" class="form-control" value="">
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
                            <input type="text" name="postalcode" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group text-left">
                        <div class="btn">
                            <button type="button" id="search">
                                <i></i>住所検索
                            </button>
                        </div>
                    </div>
                    
                    <br>
                    <div class="form-group col-sm-6">
                        <label for="prefecture">都道府県</label>
                        <div>
                            <input type="text" name="prefecture" class="form-control" value="" id="prefecture">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </form>

    
@endsection
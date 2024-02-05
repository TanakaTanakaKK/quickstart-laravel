<script src="{{ asset('/functions.js') }}" defer></script>
@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                    パスワードリセット
                </div>
        <div class="mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                    アカウント情報を編集
                </div>
                <div class="card-body px-0">
                    <div class="mt-0 mx-0">
                        <div class="panel-body">
                            @include('common.info')
                <div class="card-body">
                    <form action="{{ route('users.update_password', $user_id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="authentication_token" value="{{ $authentication_token }}">
                        <div class="mt-0 mx-0">
                            <div class="panel-body">
                                @include('common.info')
                                <label for="password" class="col-form-label font-weight-bold">新しいパスワード</label>
                                <div class="form-group col-md-12 px-0">
                                    <input type="password" name="password" class="form-control" id="password">
                                </div>
                                <label for="password_confirmation" class="col-form-label font-weight-bold">新しいパスワード(確認用)</label>
                                <div class="form-group col-md-12 px-0">
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                                </div>
                                <div class="form-group">
                                    <div class="rounded text-right">
                                        <button class="btn border">
                                            <i class="fa-solid fa-upload"></i>設定
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
                        </div>
                    </div>
                </div>
                <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="card-body px-0 py-0">
                        <div class="table-responsive">
                            <table class="table table-striped px-0">
                                <tbody>
                                    <tr>
                                        <td class="py-1 align-middle">
                                            <div>画像</div>
                                        </td>
                                            <td  class="py-1 align-middle">
                                                <input type="file" name="image_file" accept="image/png, image/jpeg, image/gif, image/webp">
                                            </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 align-middle">
                                            <div>氏名</div>
                                        </td>
                                        <td  class="py-1 align-middle">
                                            <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 align-middle">
                                            <div>氏名(カナ)</div>
                                        </td>
                                        <td  class="py-1 align-middle">
                                            <input type="text" name="kana_name" class="form-control" value="{{ $user->kana_name }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 align-middle">
                                            <div>ニックネーム</div>
                                        </td>
                                        <td  class="py-1 align-middle">
                                            <input type="text" name="nickname" class="form-control" value="{{ $user->nickname }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 align-middle">
                                            <div>性別</div>
                                        </td>
                                        <td  class="py-1 align-middle">
                                            <select name="gender" class="form-control border" id="gender">
                                                <option selected hidden value="{{ $user->gender }}">{{ \App\Enums\Gender::getDescription($user->gender) }}</option>
                                                @foreach(\App\Enums\Gender::asSelectArray() as $key => $gender)
                                                    <option value="{{ $key }}">{{ $gender }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 align-middle">
                                            <div>生年月日</div>
                                        </td>
                                        <td  class="py-1 align-middle">
                                            <input type="date" name="birthday" class="form-control" value="{{ $user->birthday }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 align-middle">
                                            <div>電話番号</div>
                                        </td>
                                        <td  class="py-1 align-middle">
                                            <input type="text" name="phone_number" class="form-control" value="{{ $user->phone_number }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 align-middle">
                                            <div>郵便番号</div>
                                        </td>
                                        <td  class="py-1 align-middle">
                                            <input type="text" name="postal_code" class="form-control" value="{{ $user->postal_code }}" id="postal_code" oninput="searchPostal()" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 align-middle">
                                            <div>都道府県</div>
                                        </td>
                                        <td  class="py-1 align-middle">
                                            <select name="prefecture" class="form-control border" id="prefecture">
                                                <option selected hidden value="{{ $user->prefecture }}">{{ \App\Enums\Prefecture::getDescription($user->prefecture) }}</option>
                                                @foreach(\App\Enums\Prefecture::asSelectArray() as $key => $prefecture)
                                                    <option value="{{ $key }}">{{ $prefecture }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 align-middle">
                                            <div>市区町村</div>
                                        </td>
                                        <td  class="py-1 align-middle">
                                            <input type="text" name="address" class="form-control" value="{{ $user->address }}" id="address">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 align-middle">
                                            <div>番地</div>
                                        </td>
                                        <td  class="py-1 align-middle">
                                            <input type="text" name="block" class="form-control" value="{{ $user->block }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 align-middle">
                                            <div>建物</div>
                                        </td>
                                        <td  class="py-1 align-middle">
                                            <input type="text" name="building" class="form-control" value="{{ $user->building }}">
                                        </td>
                                    </tr>
                                <tbody>
                            </table>
                        </div>
                    </div>  
                    <div class="card-body px-0 py-0">
                        <div class="text-right">
                            <button type="submit" class="btn border">
                                    <i class="fa-solid fa-upload"></i> 更新                            
                            </button>
                        </div>
                    </div>   
                </form>
            </div>
        </div>
    </div> 
@endsection

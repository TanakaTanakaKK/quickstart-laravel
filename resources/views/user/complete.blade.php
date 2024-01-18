@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                    登録情報
                </div>
                <div class="card-body">
                    <div class="mt-0 mx-0">
                        <div class="panel-body">
                            @include('common.info')
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped task-table">
                        <tbody>
                            <tr>
                                <td class="py-1 align-middle">
                                    <div>氏名</div>
                                </td>
                                <td  class="py-1 align-middle">
                                    <div>{{ $authenticated_user->name }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 align-middle">
                                    <div>氏名(カナ)</div>
                                </td>
                                <td  class="py-1 align-middle">
                                    <div>{{ $authenticated_user->kana_name }}</div>
                                </td>
                            </tr>
                            <td class="py-1 align-middle">
                                <div>メールアドレス</div>
                            </td>
                            <td  class="py-1 align-middle">
                                <div>{{ $authenticated_user->email }}</div>
                            </td>
                            </tr>
                            <tr>
                                <td class="py-1 align-middle">
                                    <div>ニックネーム</div>
                                </td>
                                <td  class="py-1 align-middle">
                                    <div>{{ $authenticated_user->nickname }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 align-middle">
                                    <div>性別</div>
                                </td>
                                <td  class="py-1 align-middle">
                                    <div>{{ \App\Enums\Gender::getDescription($authenticated_user->gender) }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 align-middle">
                                    <div>生年月日</div>
                                </td>
                                <td  class="py-1 align-middle">
                                    <div>{{ $authenticated_user->birthday }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 align-middle">
                                    <div>電話番号</div>
                                </td>
                                <td  class="py-1 align-middle">
                                    <div>{{ $authenticated_user->phone_number }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 align-middle">
                                    <div>郵便番号</div>
                                </td>
                                <td  class="py-1 align-middle">
                                    <div>{{ $authenticated_user->postal_code }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 align-middle">
                                    <div>都道府県</div>
                                </td>
                                <td  class="py-1 align-middle">
                                    <div>{{ \App\Enums\Prefecture::getDescription($authenticated_user->prefecture) }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 align-middle">
                                    <div>市区町村</div>
                                </td>
                                <td  class="py-1 align-middle">
                                    <div>{{ $authenticated_user->address }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 align-middle">
                                    <div>番地</div>
                                </td>
                                <td  class="py-1 align-middle">
                                    <div>{{ $authenticated_user->block }}</div>
                                </td>
                            </tr>
                            @if(isset($authenticated_user->building))
                            <tr>
                                <td class="py-1 align-middle">
                                    <div>建物</div>
                                </td>
                                <td  class="py-1 align-middle">
                                    <div>{{ $authenticated_user->building }}</div>
                                </td>
                            </tr>
                            @endif
                        <tbody>
                    </table>
                </div>
            </div>  
        </div>
    </div>
@endsection
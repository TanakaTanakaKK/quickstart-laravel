@extends('layouts.app')
@section('content')
    {{-- mt-4を追加 ナビゲーションバーとの間に余白を追加 --}}

    <div class="container mt-4">
        {{-- mx-autoを追加 テーブルが中央に --}}
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            {{-- panel-default削除。廃止、代替なし --}}
            {{-- panelをcardに変更 --}}
            {{-- borderを追加 テーブルに枠線が追加 --}}
            {{-- roundedを追加 パネルの角全てが丸みを帯びる --}}
            <div class="card border rounded">
                {{-- panel-headingはcard-headerに --}}
                {{-- pt-2 pb-2を追加 NewTaskタイトル部分の高さの調整--}}
                <div class="card-header pt-2 pb-2">
                New Task
                </div>

                {{-- panel-bodyからcard-bodyへ --}}
                <div class="card-body">
                    {{-- Display Validation Errors --}}
                    {{-- resources/views/common/errors.blade.phpをロード --}}
                    @include('common.errors')

                    {{-- New Task Form --}}
                    {{-- form-horizontalを削除。廃止 --}}
                    <form action="{{ url('task') }}" method="POST">
                        {{ csrf_field() }}
                        {{-- Task Name --}}
                        {{-- rowを追加 Taskとフォームが横並びに--}}
                        <div class="form-group row mt-0">
                            {{-- col-sm-3からcol-md-3へ ここの数値をいじればフォームの位置を調整できる
                            control-labelからcol-form-labelへ 
                            text-sm-leftを追加 576px以下の際、文字が左に移動
                            text-md-rightを追加 タブレット以上の際、文字が右に移動
                            font-weight-boldを追加 Tasksの文字が太文字
                            --}}
                            <label for="task-name" class="col-md-3 text-md-right text-sm-left col-form-label font-weight-bold">Task</label>
                            {{-- col-sm-6をcol-md-6へ ここの数値をいじればフォーム欄の長さがが変わる--}}
                            <div class="col-md-6">
                                {{-- borderを追加 AddTaskボタンと枠線の色を合わせる為 --}}
                                <input type="text" name="name" id="task-name" class="form-control border">
                            </div>
                        </div>

                        {{-- Add Task Button --}}
                        {{-- rowを追加 --}}
                        <div class="form-group row ">
                            <div class="rounded col-md-3 offset-md-3">
                                {{-- text-nowrapを追加 テキストを折り返さないように固定 --}}
                                <button type="submit" class="btn btn-default border text-nowrap">
                                    <i class="fa fa-btn fa-plus"></i>Add Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- TODO: Current Tasks --}}
                @if(count($tasks) > 0)
                {{-- panel-defaultを削除 --}}
                <div class="card border rounded mb-4">
                    {{-- card-headerを追加 タイトルがちゃんと分かれるようになった --}}
                    {{-- pt-2 pb-2を追加 現在のタスクタイトル部分の高さの調整 --}}
                    <div class="card-header rounded-top pt-2 pb-2">
                        現在のタスク
                    </div>

                    {{-- pr-3,pl-3を追加 --}}
                    <div class="card-body pr-3 pl-3">
                        <table class="table table-striped task-table">
                            {{-- テーブルヘッダ --}}
                            <thead>
                                {{-- border-top-0を追加 table上部の枠線を消すため --}}
                                {{-- pt-4を追加 テーブルの位置を少し下に下げる為 --}}
                                <th class="border-top-0 pt-2">Task</th>
                                <th class="border-top-0 pt-2">&nbsp;</th>
                            </thead>

                            {{-- テーブル本体 --}}
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        {{-- タスク名 --}}
                                        {{-- text-darkを追加 少しだけフォントカラーを薄い黒に --}}
                                        <td class="table-text pt-1 pb-1 text-dark">
                                            <div>{{ $task->name }}</div>
                                        </td>
                                        {{-- Delete Button --}}
                                        {{-- align-middleを追加　DELETEボタンを中央位置に来るように --}}
                                        <td  class="pt-1 pb-1 align-middle">
                                            {{-- リクエスト先をtaskのidを入れて指定してる --}}
                                            <form action="{{ url('task/' .$task->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            {{-- text-nowrapを追加 スマホ画面でマークと文字列が改行されないように --}}
                                            <button type="submit" class="btn btn-danger text-nowrap">
                                                <i class="fa fa-trash"></i>Delete
                                            </button>
                                        </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
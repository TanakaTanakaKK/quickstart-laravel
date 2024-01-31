@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="col-sm-offset-2 col-sm-8 mx-auto">
            <div class="card border rounded">
                <div class="card-header py-2">
                    お知らせ
                </div>
                <div class="card-body">
                    <div class="mt-0 mx-0">
                        <div class="panel-body">
                            @include('common.info')
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
@endsection
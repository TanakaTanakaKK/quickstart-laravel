{{-- resources/views/common/errors.blade.php --}}

@if(count($errors)>0)
    {{-- Form Error List --}}
    {{-- smallを追加 タブレット以下では文字列が小さくなる --}}
    {{-- pb-0を追加 アラート部分がデカかった為 --}}
    <div class="alert alert-danger small pb-0">
        <strong>おや？　何かがおかしいようです！</strong>
        
        <br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
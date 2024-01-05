@if(count($errors)>0)
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
@if(isset($successful))
<div class="alert alert-success small pb-0">
    <strong>お知らせ</strong>
    <br><br>
    <ul>
        <li>{{ $successful }}</li>
    </ul>
</div>
@endif

@if(isset($is_succeeded))
    <div class="alert alert-success small pb-0">
        <strong>お知らせ</strong>
        <br><br>
        @if(isset($authentication_message))
            <ul>
                <li>{{ $authentication_message }}</li>
            </ul>
        @endif
        @if(isset($user_message))
            <ul>
                <li>{{ $user_message }}</li>
            </ul>
        @endif
        @if(isset($task_message))
            <ul>
                <li>{{ $task_message }}</li>
            </ul>
        @endif
    </div>
@endif
@if(count($errors) > 0)
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
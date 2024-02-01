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
        @if(isset($task_updated_info_array))
            <ul>
            @foreach($task_updated_info_array as $updated_info)
                <li>{{ trans('validation.task_attributes.'.$updated_info) }}を更新しました。</li>
            @endforeach
            </ul>
        @endif
        @if(isset($created_task_name))
            <ul>
                <li>{{ $created_task_name }}をTask Listに登録しました。</li>
            </ul>
        @endif
    </div>
@endif
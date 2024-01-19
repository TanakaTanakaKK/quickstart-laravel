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
        @if(isset($user_email))
            <ul>
                <li>{{ $user_email.'宛にメールを送信しました。15分以内に登録手続きをしてください。' }}</li>
            </ul>
        @elseif(isset($email_for_reset_password))
            <ul>
                <li>{{ $email_for_reset_password.'宛にメールを送信しました。15分以内にパスワードの再設定をしてください。' }}</li>
            </ul>
        @endif

        @if(isset($completed_reset_password))
        <ul>
            <li>{{ $completed_reset_password }}</li>
        </ul>
        @endif
        
        @if(isset($email_for_reset_email))
            <ul>
                <li>{{ $email_for_reset_email }}宛にメールを送信しました。<br>メールアドレスを更新するには、15分以内にリンクにアクセスしてください。</li>
            </ul>
        @endif

        @if(isset($is_completed_reset_email))
            <ul>
                <li>メールアドレスを{{ $user_info->email }}に更新しました。</li>
            </ul>
        @endif

        @if(isset($updated_info_array))
            <ul>
            @foreach($updated_info_array as $updated_info)
                <li>{{ trans('validation.attributes.'.$updated_info) }}を更新しました。</li>
            @endforeach
            </ul>
        @endif
        
        @if(isset($user_add_messsage))
            <ul>
                <li>{{ $user_add_messsage }}</li>
            </ul>
        @endif
    </div>
@endif
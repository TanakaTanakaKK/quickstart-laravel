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
        @if(isset($is_user_created))
            <ul>
                <li>会員登録が完了しました。</li>
            </ul>
        @endif
        @if(isset($is_sent_authentication_email))
            <ul>
                <li>認証メールを送信しました。15分以内に登録手続きをしてください。</li>
            </ul>
        @endif
        @if(isset($password_reset_message))
            <ul>
                <li>{{ $password_reset_message }}</li>
            </ul>
        @else
            <ul>
                <li>パスワードを更新しました。</li>
            </ul>
        @endif
        @if(isset($proposed_update_email))
            <ul>
                <li>{{ $proposed_update_email }}宛にメールを送信しました。<br>メールアドレスを更新するには、15分以内にリンクにアクセスしてください。</li>
            </ul>
        @endif
        @if(isset($is_updated_email))
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
    </div>
@endif
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
@if(isset($successful) || isset($user_email) || isset($reset_password_email) || isset($is_user_updated))
    <div class="alert alert-success small pb-0">
        <strong>お知らせ</strong>
        <br><br>
        @if(isset($user_email))
            <ul>
                <li>{{ $user_email.'宛にメールを送信しました。15分以内に登録手続きをしてください。' }}</li>
            </ul>
        @elseif(isset($reset_password_email))
            <ul>
                <li>{{ $reset_password_email.'宛にメールを送信しました。15分以内にパスワードの再設定をしてください。' }}</li>
            </ul>
        @elseif(isset($reset_email) && isset($updated_info_array) )
            <ul>
                <li>{{ $reset_email }}宛にメールを送信しました。<br>メールアドレスを更新する為、15分以内にリンクにアクセスしてください。</li>
            </ul>
            @foreach($updated_info_array as $updated_info)
                <li>{{ $updated_info }}を更新しました。</li>
            @endforeach
        @elseif(isset($reset_email))
            <ul>
                <li>{{ $reset_email }}宛にメールを送信しました。<br>メールアドレスを更新する為、15分以内にリンクにアクセスしてください。</li>
            </ul>
        @elseif(isset($updated_info_array))
            @foreach($updated_info_array as $updated_info)
                <li>{{ $updated_info }}を更新しました。</li>
            @endforeach
        @else
            <ul>
                <li>{{ $successful }}</li>
            </ul>
        @endif
    </div>
@endif
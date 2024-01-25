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
@if(isset($successful) || isset($is_sent_authentication_email) || isset($reset_password_email))
    <div class="alert alert-success small pb-0">
        <strong>お知らせ</strong>
        <br><br>
        @if(isset($is_sent_authentication_email))
            <ul>
                <li>{{ '認証メールを送信しました。15分以内に登録手続きをしてください。' }}</li>
            </ul>
        @elseif(isset($reset_password_email))
            <ul>
                <li>{{ $reset_password_email.'宛にメールを送信しました。15分以内にパスワードの再設定をしてください。' }}</li>
            </ul>
        @else
            <ul>
                <li>{{ $successful }}</li>
            </ul>
        @endif
    </div>
@endif
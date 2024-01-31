<?php

namespace App\Http\Controllers;

use App\Enums\{
    AuthenticationStatus,
    PasswordResetStatus,
};
use App\Models\{
    Authentication,
    PasswordResetAuthentication,
    User
};
use Illuminate\Support\Facades\{
    Hash,
    Storage
};
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\{
    PasswordResetRequest,
    UserRequest,
};
use Exception;
use Imagick;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $authentication = Authentication::where('token', $request->authentication_token)
            ->where('status', AuthenticationStatus::MAIL_SENT)
            ->where('expired_at', '>', now())
            ->first();

        if(is_null($authentication)){
            return to_route('authentications.create')->withErrors(['status_error' => '会員登録に失敗しました。']);
        }
        return view('user.create');
    }

    public function store(UserRequest $request)
    {   
        $authentication = Authentication::where('token', $request->authentication_token)
            ->where('status', AuthenticationStatus::MAIL_SENT)
            ->where('expired_at', '>', now())
            ->first();

        if(is_null($authentication)){
            return to_route('authentications.create')->withErrors(['status_error' => '会員登録に失敗しました。']);
        }

        $image = new Imagick();
        $image->readImage($request->file('image_file'));
        $archive_extension = config('mimetypes')[$image->getImageMimetype()];
        
        $is_duplicated_archive_image_path = true;
        while($is_duplicated_archive_image_path){
            $archive_image_path = Str::random(rand(20, 50)).$archive_extension;
            $is_duplicated_archive_image_path = User::where('archive_image_path', $archive_image_path)->exists();
        }

        if(!is_null($image->getImageProperties("exif:*"))){
            $image->stripImage();
        }

        Storage::put('public/archive_images/'.$archive_image_path, $image);

        $is_duplicated_thumbnail_image_path = true;
        while($is_duplicated_thumbnail_image_path){
            $thumbnail_image_path = Str::random(rand(20, 50)).'.webp';
            $is_duplicated_thumbnail_image_path = User::where('thumbnail_image_path', $thumbnail_image_path)->exists();
        }

        if($archive_extension !== '.webp'){
            $image->setImageFormat('webp');
        } 
        $image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);

        Storage::put('public/thumbnail_images/'.$thumbnail_image_path, $image);
        
        $image->clear();

        try{
            $user_id = User::create([
                    'thumbnail_image_path' => $thumbnail_image_path,
                    'archive_image_path' => $archive_image_path,
                    'email' => $authentication->email,
                    'password' => Hash::make($request->password),
                    'name' => $request->name,
                    'kana_name' => $request->kana_name,
                    'nickname' => $request->nickname,
                    'gender' => $request->gender,
                    'birthday' => $request->birthday,
                    'phone_number' => str_replace('-', '', $request->phone_number),
                    'postal_code' => str_replace('-', '', $request->postal_code),
                    'prefecture' => $request->prefecture,
                    'address' => $request->address,
                    'block' => $request->block,
                    'building' => $request->building
                ])->id;
        }catch(Exception $e){
            return to_route('authentications.create')->withErrors(['register_error' => '会員登録に失敗しました。']);
        }

        $authentication->status = AuthenticationStatus::COMPLETED;
        $authentication->save();
        
        return to_route('users.complete',$user_id)->with(['is_user_created' => true]);
    }

    public function edit(Request $request)
    {
        $password_reset_authentication = PasswordResetAuthentication::where('token', $request->password_reset_token)
            ->where('status',  PasswordResetStatus::MAIL_SENT)
            ->where('expired_at', '>', now())
            ->first();

        if(is_null($password_reset_authentication)){
            return to_route('login_credential.create')->withErrors(['reset_error' => '無効なアクセスです。']);
        }

        return view('user.edit', [
            'user_id' => User::where('id', $password_reset_authentication->user_id)->first(),
            'password_reset_token' => $password_reset_authentication->token
        ]);
    }

    public function update(PasswordResetRequest $request)
    {
        $password_reset_authentication = PasswordResetAuthentication::where('token', $request->password_reset_token)
            ->where('status',  PasswordResetStatus::MAIL_SENT)
            ->where('expired_at', '>', now())
            ->first();
        if(is_null($password_reset_authentication)){
            return to_route('login_credential.create')->withErrors(['reset_error' => '無効なアクセスです。']);
        }

        $user = User::where('id', $password_reset_authentication->user_id)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        $password_reset_authentication->status = PasswordResetStatus::COMPLETED;
        $password_reset_authentication->save();

        return to_route('password_reset_authentication.complete')->with(['is_password_reset' => true]);
    }

    public function complete(Request $request, User $user)
    {     
        if(!is_null($request->session()->get('is_user_created'))){
            $request->session()->forget('is_user_created');
        }else{
            return to_route('login_credential.create');
        }

        return view('user.complete', [
            'successful' => '会員登録が完了しました。',
            'authenticated_user' => $user
        ]);
    }
}
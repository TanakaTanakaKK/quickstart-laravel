<?php

namespace App\Http\Controllers;

use App\Models\{
    User,
    Authentication,
    LoginSession,
    ResetEmail
};
use Illuminate\Support\Facades\{
    Hash,
    Storage,
    Mail
};
use App\Http\Requests\{
    UserRequest,
    UserUpdateRequest
};
use App\Enums\ResetEmailStatus;
use App\Enums\AuthenticationStatus;
use App\Mail\ResetEmailAddressMail;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;
use Imagick;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $authentication = Authentication::where('token', $request->authentication_token)
            ->where('status', AuthenticationStatus::MAIL_SENT)
            ->where('expired_at', '>', Carbon::now())
            ->first();

        if(is_null($authentication)){
            return to_route('tasks.index')->withErrors(['status_error' => '会員登録に失敗しました。']);
        }
        return view('user.create');
    }

    public function store(UserRequest $request)
    {   
        $authentication = Authentication::where('token', $request->authentication_token)
            ->where('status', AuthenticationStatus::MAIL_SENT)
            ->where('expired_at', '>', Carbon::now())
            ->first();

        if(is_null($authentication)){
            return to_route('tasks.index')->withErrors(['status_error' => '会員登録に失敗しました。']);
        }

        $image = new Imagick();
        $image->readImage($request->file('image_file'));
        $archive_format = $image->getImageFormat();
        
        $is_deprecated_archive_image_path = true;
        while($is_deprecated_archive_image_path){
            $archive_image_path = Str::random(rand(20, 50)).'.'.$archive_format;
            $is_deprecated_archive_image_path = User::where('archive_image_path', $archive_image_path)->exists();
        }

        if(!is_null($image->getImageProperties("exif:*"))){
            $image->stripImage();
        }

        Storage::put('public/archive_images/'.$archive_image_path, $image);

        $image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);
        if($image->getImageFormat() != 'webp'){
            $image->setImageFormat('webp');
        } 

        $is_deprecated_thumbnail_image_path = true;
        while($is_deprecated_thumbnail_image_path){
            $thumbnail_image_path = Str::random(rand(20, 50)).'.webp';
            $is_deprecated_thumbnail_image_path = User::where('thumbnail_image_path', $thumbnail_image_path)->exists();
        }

        Storage::put('public/thumbnail_images/'.$thumbnail_image_path, $image);

        $image->clear();

        try{
            User::create([
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
            ]);
        }catch(Exception $e){
            return to_route('tasks.index')->withErrors(['register_error' => '会員登録に失敗しました。']);
        }

        $authentication->status = AuthenticationStatus::COMPLETED;
        $authentication->save();
        
        return to_route('users.complete', $request->authentication_token)->with(['is_user_created' => true]);
    }

    public function show(Request $request)
    {
        if(is_null($request->session()->get('is_succesful_updated'))){
            return view('user.show', ['user_info' => LoginSession::where('token', $request->login_session_token)->first()->users]);
        }

        $request->session()->forget('is_succesful_updated');

        if(!is_null($request->session()->get('updated_info_array')) && !is_null($request->session()->get('reset_email'))){
            return view('user.show', [
                'user_info' => LoginSession::where('token', $request->login_session_token)->first()->users,
                'reset_email' => $request->session()->get('reset_email'),
                'updated_info_array' => $request->session()->get('updated_info_array'),
                'is_user_updated' => true
            ]);

        }else if(!is_null($request->session()->get('reset_email'))){
            return view('user.show', [
                'user_info' => LoginSession::where('token', $request->login_session_token)->first()->users,
                'reset_email' => $request->session()->get('reset_email'),
                'is_user_updated' => true
            ]);

        }else{
            return view('user.show', [
                'user_info' => LoginSession::where('token', $request->login_session_token)->first()->users,
                'updated_info_array' => $request->session()->get('updated_info_array'),
                'is_user_updated' => true
            ]);
        }
    }

    public function edit(Request $request)
    {
        if(is_null(LoginSession::where('token', $request->session()->get('login_session_token'))->first())){
            return to_route('tasks.index')->withErrors(['register_error' => 'ログインセッションが切れました。']);
        }
        return view('user.edit', ['user_info' => LoginSession::where('token', $request->session()->get('login_session_token'))->first()->users]);
        
    }

    public function update(UserUpdateRequest $request)
    {    
        if(is_null(LoginSession::where('token', $request->session()->get('login_session_token'))->first())){
            return to_route('tasks.index')->withErrors(['register_error' => 'ログインセッションが切れました。']);
        }
        $updated_info_array = [];
        $user = LoginSession::where('token', $request->session()->get('login_session_token'))->first()->users;

        foreach($request->all() as $data_name => $data){

            if(!is_null($data) && !in_array($data_name, ['email', '_token', '_method'])){

                if($data_name != 'image_file'){
                    $user->$data_name = $data;
                    $updated_info_array[] = $data_name;
                }else{

                    $image = new Imagick();
                    $image->readImage($request->file('image_file'));
                    $archive_format = $image->getImageFormat();
                    
                    $is_deprecated_archive_image_path = true;
                    while($is_deprecated_archive_image_path){
                        $archive_image_path = Str::random(rand(20, 50)).'.'.$archive_format;
                        $is_deprecated_archive_image_path = User::where('archive_image_path', $archive_image_path)->exists();
                    }
            
                    if(!is_null($image->getImageProperties("exif:*"))){
                        $image->stripImage();
                    }

                    Storage::put('public/archive_images/'.$archive_image_path, $image);
                    Storage::delete('public/archive_images/'.$user->archive_image_path);
                    $user->archive_image_path = $archive_image_path;
            
                    $image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);
                    if($image->getImageFormat() != 'webp'){
                        $image->setImageFormat('webp');
                    } 
            
                    $is_deprecated_thumbnail_image_path = true;
                    while($is_deprecated_thumbnail_image_path){
                        $thumbnail_image_path = Str::random(rand(20, 50)).'.webp';
                        $is_deprecated_thumbnail_image_path = User::where('thumbnail_image_path', $thumbnail_image_path)->exists();
                    }

                    Storage::put('public/thumbnail_images/'.$thumbnail_image_path, $image);
                    Storage::delete('public/thumbnail_images/'.$user->thumbnail_image_path);
                    $user->thumbnail_image_path = $thumbnail_image_path;
            
                    $image->clear();

                    $updated_info_array[] = 'image_file';
                }
            }
        }

        if(count($updated_info_array) >= 1){
            $user->save();
        }else if(count($updated_info_array) == 0 && is_null($request->email)){
            return to_route('users.show', $request->session()->get('login_session_token'))
                ->with(['user_info' => $user]);
        }

        if(!is_null($request->email)){
            $is_deprecated_reset_email_token = true;
            while($is_deprecated_reset_email_token){
                $reset_email_token = Str::random(rand(30,50));
                $is_deprecated_reset_email_token = ResetEmail::where('token', $reset_email_token)->exists();
            }

            Mail::to($request->email)->send(new ResetEmailAddressMail($reset_email_token));

            ResetEmail::create([
                'email' => $request->email,
                'token' => $reset_email_token,
                'user_id' => $user->id,
                'status' => ResetEmailStatus::MAIL_SENT,
                'expired_at' => Carbon::now()->addMinute(15)
            ]);

            if(count($updated_info_array) >= 1){
                return to_route('users.show', $request->session()->get('login_session_token'))
                    ->with([
                        'is_succesful_updated' => true,
                        'updated_info_array' => $updated_info_array,
                        'reset_email' => $request->email
                    ]);
            }else{
                return to_route('users.show', $request->session()->get('login_session_token'))
                    ->with([
                        'is_succesful_updated' => true,
                        'reset_email' => $request->email
                    ]);
            }
        }

        return to_route('users.show', $request->session()->get('login_session_token'))
            ->with([
                'is_succesful_updated' => true,
                'updated_info_array' => $updated_info_array
            ]);
    }

    public function complete(Request $request)
    {     
        if(is_null($request->authentication_token) || is_null($request->session()->get('is_user_created'))){
            return to_route('tasks.index');
        }

        $authenticated_user = User::whereHas('authentication', function($query) use ($request) {
            $query->where('token', $request->authentication_token)
                ->where('expired_at', '>', Carbon::now());
        })->first();

        $request->session()->forget('is_user_created');
        $request->session()->put('user_record', $authenticated_user);
        
        return view('user.complete', [
            'successful' => '会員登録が完了しました。',
            'authenticated_user' => $authenticated_user
        ]);
    }
}
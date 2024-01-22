<?php

namespace App\Http\Controllers;

use App\Enums\{
    AuthenticationStatus,
    ResetEmailStatus
};
use App\Models\{
    Authentication,
    LoginCredential,
    ResetEmail,
    User
};
use Illuminate\Support\Facades\{
    Hash,
    Storage,
    Mail
};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\{
    UserRequest,
    UserUpdateRequest
};
use App\Mail\ResetEmailAddressMail;
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
            return to_route('tasks.index')->withErrors(['status_error' => '会員登録に失敗しました。']);
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
            return to_route('tasks.index')->withErrors(['status_error' => '会員登録に失敗しました。']);
        }

        $image = new Imagick();
        $image->readImage($request->file('image_file'));
        $archive_mimetype = config('mimetypes')[$image->getImageMimetype()];
        
        $is_duplicated_archive_image_path = true;
        while($is_duplicated_archive_image_path){
            $archive_image_path = Str::random(rand(20, 50)).$archive_mimetype;
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

        if($archive_image_path === '.gif'){
            $gif_image = new Imagick();
            $gif_image->readImage($request->file('image_file'));
            $gif_image->setImageFormat('png');
            $gif_image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);
            $gif_image->setImageFormat('webp');

            Storage::put('public/thumbnail_images/'.$thumbnail_image_path, $gif_image);
            $gif_image->clear();
        }else{
            $image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);
            if($image->getImageFormat() != 'webp'){
                $image->setImageFormat('webp');
            } 
            Storage::put('public/thumbnail_images/'.$thumbnail_image_path, $image);
        }
        
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
        if(is_null($request->session()->get('is_succeeded_updated'))){
            return view('user.show', ['user_info' => LoginCredential::where('token', $request->login_credential_token)->first()->users]);
        }

        $request->session()->forget('is_succeeded_updated');
        
        $complete_messages = [
            'user_info' => LoginCredential::where('token', $request->login_credential_token)->first()->users,
            'is_succeeded' => true
        ];
        
        if(!is_null($request->session()->get('email_for_reset_email'))){
            $complete_messages += array('email_for_reset_email' => $request->session()->get('email_for_reset_email'));
        }
        if(!is_null($request->session()->get('updated_info_array'))){
            $complete_messages += array('updated_info_array' => $request->session()->get('updated_info_array'));
        }

        return view('user.show')->with($complete_messages);
    }

    public function edit(Request $request)
    {
        if(is_null(LoginCredential::where('token', $request->session()->get('login_credential_token'))->first())){
            return to_route('tasks.index')->withErrors(['register_error' => 'ログインセッションが切れました。']);
        }
        return view('user.edit', ['user_info' => LoginCredential::where('token', $request->session()->get('login_credential_token'))->first()->users]);
        
    }

    public function update(UserUpdateRequest $request)
    {    
        $login_credential = LoginCredential::where('token', $request->session()->get('login_credential_token'))->first();
        if(is_null($login_credential)){
            return to_route('tasks.index')->withErrors(['register_error' => 'ログインセッションが切れました。']);
        }
        $updated_info_array = [];
        $user = $login_credential->users;

        foreach($request->all() as $data_name => $data){

            if(!is_null($data) && !in_array($data_name, ['email', '_token', '_method'])){

                if($data_name != 'image_file'){
                    $user->$data_name = $data;
                    $updated_info_array[] = $data_name;
                }else{
                    $image = new Imagick();
                    $image->readImage($request->file('image_file'));
                    $archive_mimetype = config('mimetypes')[$image->getImageMimetype()];
                    
                    $is_duplicated_archive_image_path = true;
                    while($is_duplicated_archive_image_path){
                        $archive_image_path = Str::random(rand(20, 50)).'.'.$archive_mimetype;
                        $is_duplicated_archive_image_path = User::where('archive_image_path', $archive_image_path)->exists();
                    }
            
                    if(!is_null($image->getImageProperties("exif:*"))){
                        $image->stripImage();
                    }

                    Storage::put('public/archive_images/'.$archive_image_path, $image);
                    Storage::delete('public/archive_images/'.$user->archive_image_path);
                    $user->archive_image_path = $archive_image_path;
                    

                    $is_duplicated_thumbnail_image_path = true;
                    while($is_duplicated_thumbnail_image_path){
                        $thumbnail_image_path = Str::random(rand(20, 50)).'.webp';
                        $is_duplicated_thumbnail_image_path = User::where('thumbnail_image_path', $thumbnail_image_path)->exists();
                    }

                    if($archive_mimetype === '.gif'){
                        $gif_image = new Imagick();
                        $gif_image = $gif_image->setImageFormat('png');
                        $gif_image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);
                        $gif_image->setImageFormat('webp');

                        Storage::put('public/thumbnail_images/'.$thumbnail_image_path, $gif_image);
                
                        $gif_image->clear();
                    }else{
                        $image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);
                        if($image->getImageFormat() != 'webp'){
                            $image->setImageFormat('webp');
                        } 
    
                        Storage::put('public/thumbnail_images/'.$thumbnail_image_path, $image);
                    }

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
            return to_route('users.show', $request->session()->get('login_credential_token'));
        }

        if(!is_null($request->email)){
            $is_duplicated_reset_email_token = true;
            while($is_duplicated_reset_email_token){
                $reset_email_token = Str::random(rand(30,50));
                $is_duplicated_reset_email_token = ResetEmail::where('token', $reset_email_token)->exists();
            }

            Mail::to($request->email)->send(new ResetEmailAddressMail($reset_email_token));

            $reset_email = ResetEmail::where('user_id', $user->email)
                ->where('expired_at', '>', now())
                ->where('status', ResetEmailStatus::MAIL_SENT)
                ->first();
            
            if(!is_null($reset_email)){
                $reset_email->token = $reset_email_token;
                $reset_email->expired_at = now()->addMinute(15);
                $reset_email->save();
            }else{
                ResetEmail::create([
                    'email' => $request->email,
                    'token' => $reset_email_token,
                    'user_id' => $user->id,
                    'status' => ResetEmailStatus::MAIL_SENT,
                    'expired_at' => now()->addMinute(15)
                ]);
            }

            $complete_messages = [
                'is_succeeded_updated' => true,
                'email_for_reset_email' => $request->email
            ];
            
            if(count($updated_info_array) >= 1){
                $complete_messages += array('updated_info_array' => $updated_info_array);
            }

                return to_route('users.show', $request->session()->get('login_credential_token'))
                    ->with($complete_messages);
            
        }

        return to_route('users.show', $request->session()->get('login_credential_token'))
            ->with([
                'is_succeeded_updated' => true,
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
                ->where('expired_at', '>', now());
        })->first();

        $request->session()->forget('is_user_created');
        
        return view('user.complete', [
            'is_succeeded' => true,
            'user_add_messsage' => '会員登録が完了しました。',
            'authenticated_user' => $authenticated_user
        ]);
    }
}
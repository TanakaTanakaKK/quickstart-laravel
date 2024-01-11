<?php

namespace App\Http\Controllers;

use App\Models\{
    User,
    Authentication
};
use App\Enums\UserStatus;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;
use Carbon\Carbon;
use imagick;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $user_token = $request->token;
        $authentication = Authentication::where('token', $user_token)
            ->where('status',UserStatus::MAIL_SENT)
            ->where('expiration_at','>',Carbon::now())
            ->first();

        if(is_null($authentication)){
            return to_route('tasks.index')->withErrors(['status_error' => '会員登録に失敗しました。']);
        }
        return view('user/create');
    }

    public function store(UserRequest $request)
    {   
        $user_token = $request->user_token;
        $authentication = Authentication::where('token', $user_token)
            ->where('status',UserStatus::MAIL_SENT)
            ->where('expiration_at','>',Carbon::now())
            ->first();
                        
        if(is_null($authentication)){
            return to_route('tasks.index')->withErrors(['status_error' => '会員登録に失敗しました。']);
        }

        $image = new Imagick();
        $image->readImage($request->file('image_file'));
        $image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);

        
        if(!is_null($image->getImageProperties("exif:*"))){
            $image->stripImage();
        }
        if($image->getImageFormat() != 'webp'){
            $image->setImageFormat('webp');
        } 
        
        $save_image_path = 'user_images/'.Str::random(rand(20,50)).'.webp';
        while(!is_null(User::where('image_path',$save_image_path)->first())){
            $save_image_path = 'user_images/'.Str::random(rand(20,50)).'.webp';
        }
    
        Storage::put('public/'.$save_image_path,$image);
        $image->clear();

        $result_numbers = str_replace('-', '', [$request->phone_number,$request->postalcode]);
        try{
            User::create([
                'image_path' => $save_image_path,
                'email' => $authentication['email'],
                'password' => Hash::make($request->password),
                'name' => $request->name,
                'kana_name' => $request->kana_name,
                'nickname' => $request->nickname,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'phone_number' => $result_numbers[0],
                'postal_code' => $result_numbers[1],
                'prefecture' => $request->prefecture,
                'cities' => $request->cities,
                'block' => $request->block,
                'building' => $request->building
            ]);
        }catch(Exception $e){
            return to_route('tasks.index')->withErrors(['register_error' => '会員登録に失敗しました。']);
        }

        $authentication->status = UserStatus::COMPLETED;
        $authentication->save();
        
        return to_route('users.complete',$user_token)->with(['user_complete' => true]);
    }

    public function complete(Request $request)
    {     
        if(is_null($request->token)||is_null($request->session()->get('user_complete'))){
            return to_route('tasks.index');
        }

        $request->session()->forget('user_complete');
        return view('user/complete', [
            'successful' => '会員登録が完了しました。',
            'user' => User::where('email', Authentication::where('token',$request->token)->first()->email)->first()
        ]);
    }
}
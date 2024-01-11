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
        
        try{
            User::create([
                'email' => $authentication['email'],
                'password' => Hash::make($request->password),
                'name' => $request->name,
                'kana_name' => $request->kana_name,
                'nickname' => $request->nickname,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'phone_number' => str_replace('-', '', $request->phone_numbe),
                'postal_code' => str_replace('-', '', $request->postalcode),
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
<?php

namespace App\Http\Controllers;

use App\Models\{
    User,
    Authentication
};
use App\Enums\AuthenticationStatus;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;
use Carbon\Carbon;

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
        
        try{
            User::create([
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

    public function complete(Request $request)
    {     
        if(is_null($request->authentication_token) || is_null($request->session()->get('is_user_created'))){
            return to_route('tasks.index');
        }

        $authenticated_user = User::whereHas('authentication', function($query) use ($request) {
            $query->where('token', $request->authentication_token);
        })->first();

        $request->session()->forget('is_user_created');
        return view('user.complete', [
            'successful' => '会員登録が完了しました。',
            'authenticated_user' => $authenticated_user
        ]);
    }
}
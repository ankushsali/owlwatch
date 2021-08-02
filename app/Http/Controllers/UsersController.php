<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Users;
use App\Models\ApiToken;
use Carbon\Carbon;

class UsersController extends Controller
{
	public function userSignUp(Request $request){
		$this->validate($request, [
			'first_name' => 'required',
			'last_name' => 'required',
			'phone' => 'required',
			'email' => 'required|email',
			'address' => 'required',
		]);
		
		$check_mail = Users::where('email', $request->email)->first();
		if (!empty($check_mail)) {
			return $this->sendResponse("Email already exist!", 200, false);
		}

		$check_phone = Users::where('phone', $request->phone)->first();
		if (!empty($check_phone)) {
			return $this->sendResponse("Mobile no. already exist!", 200, false);
		}

		$time = strtotime(Carbon::now());
		$uuid = "user".$time.rand(10,99)*rand(10,99);
		
		$login_id = substr( str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 10 );

		$user = new Users;
		$user->uuid = $uuid;
		$user->login_id = $login_id;
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->phone = $request->phone;
		$user->email = $request->email;
		$user->address = $request->address;
		$user->image = "default.png";
		$result = $user->save();

		if ($result) {
			return $this->sendResponse("Signup successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function userLogin(Request $request){
		$this->validate($request, [
			'login_id' => 'required'
		]);

		$user = Users::where('login_id', $request->login_id)->first();
		if (!empty($user)) {
			$token_string = hash("sha256", rand());
			$where = ['user_id'=>$user->uuid];
			$authentication = ApiToken::where($where)->first();
			if (empty($authentication)) {
				$authentication = ApiToken::updateOrCreate(['user_id' => $user->uuid],[
					'user_id' => $user->uuid,
					'token' => $token_string,
				]);
			}
			return $this->sendResponse($user);
		}else{
			return $this->sendResponse("Login failed!", 200, false);
		}
	}
}
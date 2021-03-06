<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\ApiToken;
use App\Models\Schools;
use App\Models\SchoolUsers;
use App\Models\Semesters;
use App\Models\Settings;
use App\Models\UserImages;
use App\Models\SchoolSubscriptions;
use App\Models\Subscriptions;
use Carbon\Carbon;

class UsersController extends Controller
{
	public function userSignUp(Request $request){
		$this->validate($request, [
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required|email',
			'school_name' => 'required',
			'school_color' => 'required',
			'detention_color' => 'required',
			'type' => 'required',
		]);
		
		$getStartDate = Carbon::today();
		$startDate = $getStartDate->format('Y-m-d');
		$getEndDate = Carbon::today()->addDays(7);
		$endDate = $getEndDate->format('Y-m-d');

		$response = [];

		$check_mail = Users::where('email', $request->email)->first();
		if (!empty($check_mail)) {
			return $this->sendResponse("Email already exist!", 200, false);
		}

		$time = strtotime(Carbon::now());
		$sch_uuid = "sch".$time.rand(10,99)*rand(10,99);
		$school = new Schools;
		$school->uuid = $sch_uuid;
		$school->name = $request->school_name;
		$school->school_color = $request->school_color;
		$school->detention_color = $request->detention_color;
		$add_school = $school->save();

		$uuid = "user".$time.rand(10,99)*rand(10,99);
		$login_id = substr( str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 10 );
		$user = new Users;
		$user->uuid = $uuid;
		$user->login_id = $login_id;
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->school_uuid = $sch_uuid;
		$user->school_name = $request->school_name;
		$user->school_color = $request->school_color;
		$user->detention_color = $request->detention_color;
		$user->image = "default.png";
		$user->type = $request->type;
		$result = $user->save();

		$school_user = new SchoolUsers;
		$school_user->user_id = $user->uuid;
		$school_user->school_id = $school->uuid;
		$school_user->is_admin = "true";
		$add_school_user = $school_user->save();

		$sem_uuid = "sem".$time.rand(10,99)*rand(10,99);
		$semester = new Semesters;
		$semester->uuid = $sem_uuid;
		$semester->school_id = $school->uuid;
		$semester->name = 'First Semester';
		$semester->created_date = date('Y-m-d H:i:s');
		$save_semester = $semester->save();

		$tardy_setting = new Settings;
		$tardy_setting->school_id = $school->uuid;
		$tardy_setting->name = 'tardy_limit';
		$tardy_setting->value = '3';
		$tardy_setting->status = 'D';
		$save_tardy_setting = $tardy_setting->save();

		$subscription = new SchoolSubscriptions;
		$subscription->school_id = $school->uuid;
		$subscription->subscription = 'trial';
		$subscription->start_date = $startDate;
		$subscription->end_date = $endDate;
		$save_subscription = $subscription->save();

		if ($result) {
			$response['message'] = "Signup successfully!";
			$user = Users::with('Schools.School', 'Schools.Subscription')->where('uuid', $user->uuid)->first();
			$user['subscription_detail'] = Subscriptions::where('subscription_id', 'trial')->first();
			$response['data'] = $user;
			return $this->sendResponse($response);
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function createEmployee(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'is_admin' => 'required|in:true,false',
		]);

		$school = Schools::where('uuid', $request->school_id)->first();
		if (!empty($school)) {
			$time = strtotime(Carbon::now());
			$uuid = "user".$time.rand(10,99)*rand(10,99);
			$login_id = substr( str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 10 );
			$user = new Users;
			$user->uuid = $uuid;
			$user->login_id = $login_id;
			$user->school_uuid = $school->uuid;
			$user->school_name = $school->name;
			$user->school_color = $school->school_color;
			$user->detention_color = $school->detention_color;
			$user->image = "default.png";
			$result = $user->save();

			$school_user = new SchoolUsers;
			$school_user->user_id = $user->uuid;
			$school_user->school_id = $school->uuid;
			$school_user->is_admin = $request->is_admin;
			$add_school_user = $school_user->save();

			if ($result) {
				return $this->sendResponse($login_id);
			}else{
				return $this->sendResponse("Sorry, Something went wrong!", 200, false);
			}
		}else{
			return $this->sendResponse("Sorry, School not found!", 200, false);
		}
	}

	public function updateProfile(Request $request){
		$this->validate($request, [
			'user_id' => 'required',
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required|email',
			'type' => 'required',
		]);

		$user = Users::with('Schools.School')->where('uuid', $request->user_id)->first();
		if (!empty($user)) {
			if ($request->email !== $user->email) {
				$emailCheck = Users::where('email', $request->email)->first();
				if (!empty($emailCheck)) {
					return $this->sendResponse("Email already exist!", 200, false);
				}
			}

			$update = Users::where('uuid', $request->user_id)->update([
				'first_name'=>$request->first_name,
				'last_name'=>$request->last_name,
				'email'=>$request->email,
				'type'=>$request->type,
			]);

			if ($update) {
				$updatedUser = Users::with('Schools.School')->where('uuid', $request->user_id)->first();
				return $this->sendResponse($updatedUser);
			}else{
				return $this->sendResponse("Sorry, Something went wrong!", 200, false);
			}
		}else{
			return $this->sendResponse("Sorry, User not found!", 200, false);
		}
	}

	public function getSchoolUsers(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'user_id' => 'required',
		]);

		$school_users = SchoolUsers::where('school_id', $request->school_id)->pluck('user_id')->toArray();

		if (sizeof($school_users) > 0) {
			$all_users = [];
			$ST = [];
			$SC = [];
			$CL = [];
			$SA = [];
			$users = Users::with('Schools.School')->whereIn('uuid', $school_users)->get();
			foreach ($users as $user) {
				if ($user->uuid != $request->user_id) {
					if ($user->type == "ST") {
						$ST[] = $user;
					}elseif ($user->type == "SC") {
						$SC[] = $user;
					}elseif ($user->type == "CL") {
						$CL[] = $user;
					}elseif ($user->type == "SA") {
						$SA[] = $user;
					}
				}
			}
			$all_users = ['ST'=>$ST, 'SC'=>$SC, 'CL'=>$CL, 'SA'=>$SA];

			if (sizeof($users) > 0) {
				return $this->sendResponse($all_users);
			}else{
				return $this->sendResponse("Sorry, Users not found!", 200, false);
			}
		}else{
			return $this->sendResponse("Sorry, Users not found!", 200, false);
		}
	}

	public function updatePermission(Request $request){
		$this->validate($request, [
			'user_id' => 'required',
			'school_id' => 'required',
			'status' => 'required',
		]);

		$user = Users::where('uuid', $request->user_id)->first();
		if (empty($user)) {
			return $this->sendResponse("Sorry, User not found!", 200, false);
		}

		$school = Schools::where('uuid', $request->school_id)->first();
		if (empty($school)) {
			return $this->sendResponse("Sorry, School not found!", 200, false);
		}

		$update = SchoolUsers::where(['user_id'=>$request->user_id, 'school_id'=>$request->school_id])->update(['is_admin'=>$request->status]);

		if ($update) {
			return $this->sendResponse("Permission updated successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function revokeUser(Request $request){
		$this->validate($request, [
			'user_id' => 'required',
			'school_id' => 'required',
		]);

		$user = Users::where('uuid', $request->user_id)->first();
		if (empty($user)) {
			return $this->sendResponse("Sorry, User not found!", 200, false);
		}

		$school = Schools::where('uuid', $request->school_id)->first();
		if (empty($school)) {
			return $this->sendResponse("Sorry, School not found!", 200, false);
		}

		$revoke = SchoolUsers::where(['user_id'=>$request->user_id, 'school_id'=>$request->school_id])->delete();

		if ($revoke) {
			return $this->sendResponse("User revoked from school!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function userLogin(Request $request){
		$this->validate($request, [
			'login_id' => 'required'
		]);

		$user = Users::with('Schools.School', 'Schools.Subscription.Subscription')->where('login_id', $request->login_id)->first();
		if ($user->first_name == '' && $user->last_name == '' && $user->email == '') {
			$user->is_verified = false;
		}else{
			$user->is_verified = true;
		}

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

	public function uploadStudentImage(Request $request){
		$this->validate($request, [
			'student_id' => 'required',
			'school_id' => 'required',
			'image' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$path = app()->basePath('public/user-images/');
		$fileName = $this->upload($path, $request->image);
		$image = new UserImages;
		$image->student_id = $request->student_id;
		$image->school_id = $request->school_id;
		$image->semester_id = $semester->uuid;
		$image->image = $fileName;
		$save_image = $image->save();

		if ($save_image) {
			return $this->sendResponse("Image uploaded successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function getStudentImage(Request $request){
		$this->validate($request, [
			'student_id' => 'required',
			'school_id' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$image = UserImages::where(['student_id'=>$request->student_id, 'school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->orderBy('created_at', 'desc')->first();

		if (!empty($image)) {
			$image['image'] = env('APP_URL').'public/user-images/'.$image->image;
			return $this->sendResponse($image);
		}else{
			return $this->sendResponse("Sorry, Image not found!", 200, false);
		}
	}
}
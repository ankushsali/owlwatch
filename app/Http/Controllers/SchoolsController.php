<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schools;
use App\Models\SchoolUsers;
use Carbon\Carbon;

class SchoolsController extends Controller
{
	public function addSchool(Request $request){
		$this->validate($request, [
			'name' => 'required',
			'address' => 'required',
			'color' => 'required',
		]);

		$time = strtotime(Carbon::now());
		$uuid = "sch".$time.rand(10,99)*rand(10,99);

		$school = new Schools;
		$school->uuid = $uuid;
		$school->name = $request->name;
		$school->address = $request->address;
		$school->color = $request->color;
		$result = $school->save();

		if ($result) {
			return $this->sendResponse("School added successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function assignSchool(Request $request){
		$this->validate($request, [
			'user_id' => 'required',
			'school_id' => 'required',
			'role' => 'required',
			'color' => 'required',
		]);

		$assign_school = new SchoolUsers;
		$assign_school->user_id = $request->user_id;
		$assign_school->school_id = $request->school_id;
		$assign_school->role = $request->role;
		$assign_school->color = $request->color;
		$result = $assign_school->save();

		if ($result) {
			return $this->sendResponse("School assigned successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}
}
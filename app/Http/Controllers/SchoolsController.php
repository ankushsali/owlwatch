<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schools;
use App\Models\SchoolUsers;
use App\Models\Locations;
use App\Models\Durations;
use App\Models\HallPass;
use Carbon\Carbon;

class SchoolsController extends Controller
{
	public function addSchool(Request $request){
		$this->validate($request, [
			'name' => 'required',
			'school_color' => 'required',
			'detention_color' => 'detention_color',
		]);

		$time = strtotime(Carbon::now());
		$uuid = "sch".$time.rand(10,99)*rand(10,99);

		$school = new Schools;
		$school->uuid = $uuid;
		$school->name = $request->name;
		$school->school_color = $request->school_color;
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
			'school_color' => 'required',
		]);

		$assign_school = new SchoolUsers;
		$assign_school->user_id = $request->user_id;
		$assign_school->school_id = $request->school_id;
		$assign_school->role = $request->role;
		$assign_school->color = $request->school_color;
		$result = $assign_school->save();

		if ($result) {
			return $this->sendResponse("School assigned successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function updateSchool(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'name' => 'required',
			'school_color' => 'required',
			'detention_color' => 'required',
		]);

		$update = Schools::where('uuid', $request->school_id)->update([
			'name'=>$request->name,
			'school_color'=>$request->school_color,
			'detention_color'=>$request->detention_color,
		]);

		if ($update) {
			return $this->sendResponse("School updated successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function addLocation(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'name' => 'required',
		]);

		$time = strtotime(Carbon::now());
		$uuid = "loc".$time.rand(10,99)*rand(10,99);

		$add_location = new Locations;
		$add_location->uuid = $uuid;
		$add_location->school_id = $request->school_id;
		$add_location->name = $request->name;
		$save_location = $add_location->save();

		if ($save_location) {
			return $this->sendResponse("Location added successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function updateLocation(Request $request){
		$this->validate($request, [
			'location_id' => 'required',
			'name' => 'required',
		]);

		$update = Locations::where('uuid', $request->location_id)->update([
			'name'=>$request->name,
		]);

		if ($update) {
			return $this->sendResponse("Location updated successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function getAllLocations(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
		]);

		$locations = Locations::where('school_id', $request->school_id)->get();

		if (sizeof($locations) > 0) {
			return $this->sendResponse($locations);
		}else{
			return $this->sendResponse("Sorry, Locations not found!", 200, false);
		}
	}

	public function deleteLocation(Request $request){
		$this->validate($request, [
			'location_id' => 'required',
		]);

		$delete = Locations::where('uuid', $request->location_id)->delete();

		if ($delete) {
			return $this->sendResponse("Location deleted successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function addDuration(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'duration' => 'required',
		]);

		$time = strtotime(Carbon::now());
		$uuid = "dur".$time.rand(10,99)*rand(10,99);

		$add_duration = new Durations;
		$add_duration->uuid = $uuid;
		$add_duration->school_id = $request->school_id;
		$add_duration->duration = $request->duration;
		$save_duration = $add_duration->save();

		if ($save_duration) {
			return $this->sendResponse("Duration added successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function updateDuration(Request $request){
		$this->validate($request, [
			'duration_id' => 'required',
			'duration' => 'required',
		]);

		$update = Durations::where('uuid', $request->duration_id)->update([
			'duration'=>$request->duration,
		]);

		if ($update) {
			return $this->sendResponse("Duration updated successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function getAllDurations(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
		]);

		$durations = Durations::where('school_id', $request->school_id)->get();

		if (sizeof($durations) > 0) {
			return $this->sendResponse($durations);
		}else{
			return $this->sendResponse("Sorry, Durations not found!", 200, false);
		}
	}

	public function deleteDuration(Request $request){
		$this->validate($request, [
			'duration_id' => 'required',
		]);

		$delete = Durations::where('uuid', $request->duration_id)->delete();

		if ($delete) {
			return $this->sendResponse("Duration deleted successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function createHallPass(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'student_name' => 'required',
			'location' => 'required',
			'duration' => 'required',
			'comments' => 'required',
		]);

		$time = strtotime(Carbon::now());
		$uuid = "hall".$time.rand(10,99)*rand(10,99);

		$hall_pass = new HallPass;
		$hall_pass->uuid = $uuid;
		$hall_pass->school_id = $request->school_id;
		$hall_pass->student_name = $request->student_name;
		$hall_pass->location = $request->location;
		$hall_pass->duration = $request->duration;
		$hall_pass->comments = $request->comments;
		$save_hall_pass = $hall_pass->save();

		if ($save_hall_pass) {
			return $this->sendResponse("Hall pass created successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function getAllHallPasses(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
		]);

		$all_hallpass = HallPass::with('Location', 'Duration')->where('school_id', $request->school_id)->get();

		if (sizeof($all_hallpass) > 0) {
			return $this->sendResponse($all_hallpass);
		}else{
			return $this->sendResponse("Sorry, Hall passes not found!", 200, false);
		}
	}
}
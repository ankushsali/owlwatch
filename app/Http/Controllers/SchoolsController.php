<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schools;
use App\Models\SchoolUsers;
use App\Models\Locations;
use App\Models\Durations;
use App\Models\Semesters;
use App\Models\StudentContacts;
use App\Models\StudentData;
use App\Models\StudentSchedules;
use App\Models\DetentionReasons;
use App\Models\Tardy;
use App\Models\Periods;
use App\Models\Detentions;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;

class SchoolsController extends Controller
{
	public function addSchool(Request $request){
		$this->validate($request, [
			'name' => 'required',
			'school_color' => 'required',
			'detention_color' => 'required',
			'user_id' => 'required',
		]);

		$time = strtotime(Carbon::now());
		$uuid = "sch".$time.rand(10,99)*rand(10,99);

		$school = new Schools;
		$school->uuid = $uuid;
		$school->name = $request->name;
		$school->school_color = $request->school_color;
		$school->detention_color = $request->detention_color;
		$result = $school->save();

		$school_user = new SchoolUsers;
		$school_user->user_id = $request->user_id;
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
		]);

		$assign_school = new SchoolUsers;
		$assign_school->user_id = $request->user_id;
		$assign_school->school_id = $request->school_id;
		$assign_school->is_admin = "true";
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
		$add_duration->default = 'NO';
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

	public function setDefaultDuration(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'duration_id' => 'required',
		]);

		Durations::where('school_id',$request->school_id)->update(['default'=>'NO']);

		$update = Durations::where(['uuid'=>$request->duration_id, 'school_id'=>$request->school_id])->update(['default'=>'YES']);

		if ($update) {
			return $this->sendResponse("Default duration set successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
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

	public function createSemester(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'name' => 'required',
			'created_date' => 'required',
			'pre_sem_data' => 'required|in:Y,N',
		]);

		$pre_semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$time = strtotime(Carbon::now());
		$uuid = "sem".$time.rand(10,99)*rand(10,99);

		$semester = new Semesters;
		$semester->uuid = $uuid;
		$semester->school_id = $request->school_id;
		$semester->name = $request->name;
		$semester->created_date = $request->created_date;
		$save_semester = $semester->save();

		if ($request->pre_sem_data == 'Y') {
			if (!empty($pre_semester)) {
				$StudentContacts = StudentContacts::where(['school_id'=>$request->school_id, 'semester_id'=>$pre_semester->uuid])->get();
				if (sizeof($StudentContacts) > 0) {
					foreach ($StudentContacts as $SC) {
						$student_contact = new StudentContacts;
						$student_contact->school_id = $SC->school_id;
						$student_contact->semester_id = $semester->uuid;
						$student_contact->student_id = $SC->student_id;
						$student_contact->name = $SC->name;
						$student_contact->phone = $SC->phone;
						$student_contact->phone_type = $SC->phone_type;
						$student_contact->email = $SC->email;
						$student_contact->save();
					}
				}

				$StudentData = StudentData::where(['school_id'=>$request->school_id, 'semester_id'=>$pre_semester->uuid])->get();
				if (sizeof($StudentData) > 0) {
					foreach ($StudentData as $SD) {
						$student_data = new StudentData;
						$student_data->school_id = $SD->school_id;
						$student_data->semester_id = $semester->uuid;
						$student_data->first_name = $SD->first_name;
						$student_data->last_name = $SD->last_name;
						$student_data->student_id = $SD->student_id;
						$student_data->grade = $SD->grade;
						$student_data->dob = $SD->dob;
						$student_data->counselor = $SD->counselor;
						$student_data->locker_number = $SD->locker_number;
						$student_data->locker_combination = $SD->locker_combination;
						$student_data->parking_space = $SD->parking_space;
						$student_data->license_plate = $SD->license_plate;
						$student_data->save();
					}
				}

				$StudentSchedules = StudentSchedules::where(['school_id'=>$request->school_id, 'semester_id'=>$pre_semester->uuid])->get();
				if (sizeof($StudentSchedules) > 0) {
					$periods = [];
					foreach ($StudentSchedules as $SS) {
						$periods[] = $student['Period'];

						$student_schedule = new StudentSchedules;
						$student_schedule->school_id = $SS->school_id;
						$student_schedule->semester_id = $semester->uuid;
						$student_schedule->student_id = $SS->student_id;
						$student_schedule->period = $SS->period;
						$student_schedule->teacher = $SS->teacher;
						$student_schedule->room_number = $SS->room_number;
						$student_schedule->class_name = $SS->class_name;
						$student_schedule->semester = $SS->semester;
						$student_schedule->save();
					}

					foreach (array_unique($periods) as $single_period) {
						$time = strtotime(Carbon::now());
						$period_uuid = "prd".$time.rand(10,99)*rand(10,99);

						$period = new Periods;
						$period->uuid = $period_uuid;
						$period->school_id = $request->school_id;
						$period->semester_id = $semester->uuid;
						$period->period = $single_period;
						$period->save();
					}
				}
			}	
		}

		if ($save_semester) {
			return $this->sendResponse("Semester created successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function updateSemester(Request $request){
		$this->validate($request, [
			'semester_id' => 'required',
			'name' => 'required',
		]);

		$update = Semesters::where('uuid', $request->semester_id)->update(['name'=>$request->name]);

		if ($update) {
			return $this->sendResponse("Semester updated successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function getSchoolSemesters(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
		]);

		$semesters = Semesters::where('school_id', $request->school_id)->get();

		if (sizeof($semesters) > 0) {
			return $this->sendResponse($semesters);
		}else{
			return $this->sendResponse("Sorry, Data not found!", 200, false);
		}
	}

	public function addDetentionReason(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'reason' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$time = strtotime(Carbon::now());
		$uuid = "rsn".$time.rand(10,99)*rand(10,99);

		$reason = new DetentionReasons;
		$reason->uuid = $uuid;
		$reason->school_id = $request->school_id;
		$reason->semester_id = $semester->uuid;
		$reason->name = $request->reason;
		$save_reason = $reason->save();

		if ($save_reason) {
			return $this->sendResponse("Detention reason added successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function createDetentionReason(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'reason' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$time = strtotime(Carbon::now());
		$uuid = "rsn".$time.rand(10,99)*rand(10,99);

		$reason = new DetentionReasons;
		$reason->uuid = $uuid;
		$reason->school_id = $request->school_id;
		$reason->semester_id = $semester->uuid;
		$reason->name = $request->reason;
		$save_reason = $reason->save();

		if ($save_reason) {
			return $this->sendResponse("Detention reason added successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function getDetentionReasons(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$reasons = DetentionReasons::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->get();

		if (sizeof($reasons) > 0) {
			return $this->sendResponse($reasons);
		}else{
			return $this->sendResponse("Sorry, Data not found!", 200, false);
		}
	}

	public function tardyRegularReport(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'start_date' => 'required',
			'end_date' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$tardy_array = [];
		$student_ids = Tardy::whereBetween('created_at', [$request->start_date, $request->end_date])->where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->pluck('student_id')->toArray();

		if (sizeof($student_ids) > 0) {
			foreach ($student_ids as $student_id) {
				$tardy = Tardy::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student_id])->first();

				$period = Periods::where(['uuid'=>$tardy->period_id, 'school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->first();

				$student = StudentData::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student_id])->first();

				$push_array = [
					'date'=>date('Y-m-d', strtotime($tardy->created_at)),
					'time'=>date('H:i:s', strtotime($tardy->created_at)),
					'period'=>$period->period,
					'student_id'=>$student_id,
					'student_name'=>$student->first_name.' '.$student->last_name
				];

				array_push($tardy_array, $push_array);
			}
		}

		$dataFirst = ['tardy_array'=>$tardy_array];

		$filename = 'tardyRegularReport.pdf';
		$pdf = PDF::loadView('tardyRegularReport', $dataFirst);
		return $pdf->stream($filename);
	}

	public function tardyGroupedReport(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'start_date' => 'required',
			'end_date' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$tardy_array = [];
		$student_ids = Tardy::whereBetween('created_at', [$request->start_date, $request->end_date])->where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->pluck('student_id')->toArray();

		if (sizeof($student_ids) > 0) {
			foreach (array_unique($student_ids) as $student_id) {
				$tardy = Tardy::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student_id])->first();

				$tardy_count = Tardy::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student_id])->count();

				$period = Periods::where(['uuid'=>$tardy->period_id, 'school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->first();

				$student = StudentData::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student_id])->first();

				$push_array = [
					'student_id'=>$student_id,
					'student_name'=>$student->first_name.' '.$student->last_name,
					'grade'=>$student->grade,
					'tardy_count'=>$tardy_count,
					'period_tardy_count'=>$tardy_count
				];

				array_push($tardy_array, $push_array);
			}
		}

		$dataFirst = ['tardy_array'=>$tardy_array];

		$filename = 'tardyGroupedReport.pdf';
		$pdf = PDF::loadView('tardyGroupedReport', $dataFirst);
		return $pdf->stream($filename);
	}

	public function detentionRegularReport(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'start_date' => 'required',
			'end_date' => 'required',
			'reasons' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$detention_array = [];
		if ($request->reasons == 'null') {
			$student_ids = Detentions::whereBetween('created_at', [$request->start_date, $request->end_date])->where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->pluck('student_id')->toArray();
		}else{
			$student_ids = Detentions::whereBetween('created_at', [$request->start_date, $request->end_date])->where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->whereIn('reason_id', $request->reasons)->pluck('student_id')->toArray();
		}

		if (sizeof($student_ids) > 0) {
			foreach ($student_ids as $student_id) {
				$detention = Detentions::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student_id])->first();

				$reason = DetentionReasons::where(['uuid'=>$detention->reason_id, 'school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->first();

				$student = StudentData::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student_id])->first();

				$push_array = [
					'date'=>date('Y-m-d', strtotime($detention->created_at)),
					'time'=>date('H:i:s', strtotime($detention->created_at)),
					'student_id'=>$student_id,
					'student_name'=>$student->first_name.' '.$student->last_name,
					'reason'=>$reason->name,
					'comments'=>$detention->comment,
				];

				array_push($detention_array, $push_array);
			}
		}

		$dataFirst = ['detention_array'=>$detention_array];

		$filename = 'detentionRegularReport.pdf';
		$pdf = PDF::loadView('detentionRegularReport', $dataFirst);
		return $pdf->stream($filename);
	}

	public function detentionGroupedReport(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'start_date' => 'required',
			'end_date' => 'required',
			'reasons' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$detention_array = [];
		if ($request->reasons == 'null') {
			$student_ids = Detentions::whereBetween('created_at', [$request->start_date, $request->end_date])->where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->pluck('student_id')->toArray();
		}else{
			$student_ids = Detentions::whereBetween('created_at', [$request->start_date, $request->end_date])->where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->whereIn('reason_id', $request->reasons)->pluck('student_id')->toArray();
		}

		if (sizeof($student_ids) > 0) {
			foreach (array_unique($student_ids) as $student_id) {
				$detention = Detentions::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student_id])->first();

				$detention_count = Detentions::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student_id])->count();

				$reason = DetentionReasons::where(['uuid'=>$detention->reason_id, 'school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->first();

				$student = StudentData::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student_id])->first();

				$push_array = [
					'student_id'=>$student_id,
					'student_name'=>$student->first_name.' '.$student->last_name,
					'grade'=>$student->grade,
					'detention_count'=>$detention_count,
					'reason_detention_count'=>$detention_count,
				];

				array_push($detention_array, $push_array);
			}
		}

		$dataFirst = ['detention_array'=>$detention_array];

		$filename = 'detentionGroupedReport.pdf';
		$pdf = PDF::loadView('detentionGroupedReport', $dataFirst);
		return $pdf->stream($filename);
	}

	public function allSchools(Request $request){
		$schools = Schools::get();

		if (sizeof($schools) > 0) {
			return $this->sendResponse($schools);
		}else{
			return $this->sendResponse("Sorry, Data not found!", 200, false);
		}
	}

	public function userSchools(Request $request){
		$this->validate($request, [
			'user_id' => 'required',
		]);

		$user_schools = SchoolUsers::with('School')->where('user_id', $request->user_id)->get();

		if (sizeof($user_schools) > 0) {
			return $this->sendResponse($user_schools);
		}else{
			return $this->sendResponse("Sorry, Data not found!", 200, false);
		}
	}
}
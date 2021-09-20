<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentContacts;
use App\Models\StudentData;
use App\Models\StudentSchedules;
use App\Models\HallPass;
use App\Models\Semesters;
use App\Models\Periods;
use App\Models\Tardy;
use App\Models\Detentions;
use App\Models\Settings;
use Carbon\Carbon;

class StudentsController extends Controller
{
	function csvToArray($filename = ''){
		if (!file_exists($filename) || !is_readable($filename))
		return false;

		$header = null;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== false) {
			while (($row = fgetcsv($handle, 1000, ',')) !== false) {
				if (!$header) {
					$header = $row;
				}else {
					$data[] = array_combine($header, $row);
				}
			}
			fclose($handle);
		}

		return $data;
	}

	public function importStudentContacts(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'file' => 'required',
			'student_id' => 'required',
			'name' => 'required',
			'phone' => 'nullable',
			'phone_type' => 'nullable',
			'email' => 'nullable',
		]);

		$result = 0;

		$file = $request->file;

		$studentArr = $this->csvToArray($file);
		
		$exist_flag = 0;

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		foreach ($studentArr as $student) {
			StudentContacts::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student['Student ID']])->delete();

			if (!isset($student[$request->student_id]) || !isset($student[$request->name])) {
				return $this->sendResponse("Data is not formatted in this file!",200,false);
			}

			$exist_flag = 1;

			$student_contact = new StudentContacts;
			$student_contact->school_id = $request->school_id;
			$student_contact->semester_id = $semester->uuid;
			$student_contact->student_id = $student['Student ID'];
			$student_contact->name = $student['Name'];
			$student_contact->phone = $student['Phone'];
			$student_contact->phone_type = $student['Phone Type'];
			$student_contact->email = $student['Email'];
			$result = $student_contact->save();
		}

		if ($result != 0) {
			return $this->sendResponse("Student contacts imported successfully.");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!",200,false);
		}
	}

	public function importStudentData(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'file' => 'required',
			'first_name' => 'required',
			'last_name' => 'required',
			'student_id' => 'required',
			'grade' => 'nullable',
			'dbo' => 'nullable',
			'counselor' => 'nullable',
			'locker_number' => 'nullable',
			'locker_combination' => 'nullable',
			'parking_space' => 'nullable',
			'license_plate' => 'nullable',
		]);

		$result = 0;

		$file = $request->file;

		$studentArr = $this->csvToArray($file);
		
		$exist_flag = 0;

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		foreach ($studentArr as $student) {
			if (!isset($student[$request->first_name]) || !isset($student[$request->last_name]) || !isset($student[$request->student_id])) {
				return $this->sendResponse("Data is not formatted in this file!",200,false);
			}

			$exist_flag = 1;

			$student_data = new StudentData;
			$student_data->school_id = $request->school_id;
			$student_data->semester_id = $semester->uuid;
			$student_data->first_name = $student['First Name'];
			$student_data->last_name = $student['Last Name'];
			$student_data->student_id = $student['Student ID'];
			$student_data->grade = $student['Grade'];
			$student_data->dob = $student['Date of Birth'];
			$student_data->counselor = $student['Counselor'];
			$student_data->locker_number = $student['Locker Number'];
			$student_data->locker_combination = $student['Locker Combination'];
			$student_data->parking_space = $student['Parking Space'];
			$student_data->license_plate = $student['License Plate'];
			$result = $student_data->save();
		}

		if ($result != 0) {
			return $this->sendResponse("Student data imported successfully.");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!",200,false);
		}
	}

	public function importStudentSchedules(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'file' => 'required',
			'student_id' => 'required',
			'period' => 'required',
			'teacher' => 'nullable',
			'room_number' => 'nullable',
			'class_name' => 'required',
			'semester' => 'nullable',
		]);

		$result = 0;

		$file = $request->file;

		$studentArr = $this->csvToArray($file);
		
		$exist_flag = 0;

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$periods = [];
		foreach ($studentArr as $student) {
			StudentSchedules::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student['Student ID']])->delete();
			
			$periods[] = $student['Period'];
			if (!isset($student[$request->student_id]) || !isset($student[$request->period]) || !isset($student[$request->class_name])) {
				return $this->sendResponse("Data is not formatted in this file!",200,false);
			}
			
			$exist_flag = 1;

			$student_schedule = new StudentSchedules;
			$student_schedule->school_id = $request->school_id;
			$student_schedule->semester_id = $semester->uuid;
			$student_schedule->student_id = $student['Student ID'];
			$student_schedule->period = $student['Period'];
			$student_schedule->teacher = $student['Teacher'];
			$student_schedule->room_number = $student['Room Number'];
			$student_schedule->class_name = $student['Class Name'];
			$student_schedule->semester = $student['Semester'];
			$result = $student_schedule->save();
		}

		foreach (array_unique($periods) as $single_period) {
			$time = strtotime(Carbon::now());
			$uuid = "prd".$time.rand(10,99)*rand(10,99);

			$period = new Periods;
			$period->uuid = $uuid;
			$period->school_id = $request->school_id;
			$period->semester_id = $semester->uuid;
			$period->period = $single_period;
			$period->save();
		}

		if ($result != 0) {
			return $this->sendResponse("Student schedules imported successfully.");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!",200,false);
		}
	}

	public function getStudentContacts(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();
		
		$result = StudentContacts::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->get();
		if (sizeof($result) > 0) {
			return $this->sendResponse($result);
		}else{
			return $this->sendResponse("Sorry, Student contacts not found!",200,false);
		}
	}

	public function getStudentData(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$result = StudentData::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->get();

		$all_students = [];
		if (sizeof($result) > 0) {
			foreach ($result as $student) {
				$tardy = Tardy::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$student->student_id])->get();
				$student['tardy_count'] = sizeof($tardy);

				$all_students[] = $student;
			}

			return $this->sendResponse($all_students);
		}else{
			return $this->sendResponse("Sorry, Student data not found!",200,false);
		}
	}

	public function getStudentSchedules(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$result = StudentSchedules::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->get();
		if (sizeof($result) > 0) {
			return $this->sendResponse($result);
		}else{
			return $this->sendResponse("Sorry, Student schedules not found!",200,false);
		}
	}

	public function getSingleStudent(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'student_id' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$student = StudentData::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$request->student_id])->first();

		if (!empty($student)) {
			$StudentSchedules = StudentSchedules::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$request->student_id])->get();
			$StudentContacts = StudentContacts::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$request->student_id])->get();
			$tardy = Tardy::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$request->student_id])->get();
			$student['tardy_count'] = sizeof($tardy);
			$student['student_schedules'] = $StudentSchedules;
			$student['student_contacts'] = $StudentContacts;
			return $this->sendResponse($student);
		}else{
			return $this->sendResponse("Sorry, Student not found!",200,false);
		}
	}

	public function createHallPass(Request $request){
		$this->validate($request, [
			'user_id' => 'required',
			'school_id' => 'required',
			'student_name' => 'required',
			'location' => 'required',
			'duration' => 'required',
			'comments' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$time = strtotime(Carbon::now());
		$uuid = "hall".$time.rand(10,99)*rand(10,99);

		$hall_pass = new HallPass;
		$hall_pass->uuid = $uuid;
		$hall_pass->user_id = $request->user_id;
		$hall_pass->school_id = $request->school_id;
		$hall_pass->semester_id = $semester->uuid;
		$hall_pass->student_name = $request->student_name;
		$hall_pass->location = $request->location;
		$hall_pass->duration = $request->duration;
		$hall_pass->comments = $request->comments;
		$hall_pass->status = 'N/A';
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

		$all_hallpass = [];

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();
		
		$hallpasses = HallPass::with('Location', 'Duration', 'StudentData', 'User')->where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->get();

		if (sizeof($hallpasses) > 0) {
			foreach ($hallpasses as $hallpass) {
				$expire_at = $hallpass->Duration->duration*60 + strtotime($hallpass->created_at);
				$minutes = (time() - strtotime($hallpass->created_at)) / 60;

				if ($hallpass->status == 'EX') {
					$hallpass['expired'] = true;
					$hallpass['expire_in'] = 'expired';
				}elseif ($minutes > $hallpass->Duration->duration) {
					$hallpass['expire_in'] = 'expired';
					$hallpass['expired'] = true;
				}else{
					$hallpass['expire_in'] = round($hallpass->Duration->duration - $minutes, 0);
					$hallpass['expired'] = false;
				}
				
				$hallpass['expire_at'] = date('Y-m-d H:i:s', $expire_at);
				$all_hallpass[] = $hallpass;
			}
		
			return $this->sendResponse($all_hallpass);
		}else{
			return $this->sendResponse("Sorry, Hall passes not found!", 200, false);
		}
	}

	public function expireHallPass(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'hallpass_id' => 'required',
		]);

		$expire_hallpass = HallPass::where(['uuid'=>$request->hallpass_id, 'school_id'=>$request->school_id])->update(['status'=>'EX']);

		if ($expire_hallpass) {
			return $this->sendResponse("Hall pass expired successfully!");
		}else{
			return $this->sendResponse("Sorry, Hall passes not found or Something went wrong!", 200, false);
		}
	}

	public function getPeriods(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();
		
		$periods = Periods::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->get();

		if (sizeof($periods) > 0) {
			return $this->sendResponse($periods);
		}else{
			return $this->sendResponse("Sorry, Periods not found!", 200, false);
		}
	}

	public function createTardy(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'period_id' => 'required',
			'student_id' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$tardy_setting = Settings::where(['school_id'=>$request->school_id, 'name'=>'tardy_limit'])->first();

		if ($tardy_setting->status == 'E') {
			$tardy_count = Tardy::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$request->student_id])->count();
			if ($tardy_count >= $tardy_setting->value) {
				return $this->sendResponse("Tardy limit is over, Ned to create detention!", 200, false);
			}
		}

		$time = strtotime(Carbon::now());
		$uuid = "trdy".$time.rand(10,99)*rand(10,99);

		$tardy = new Tardy;
		$tardy->uuid = $uuid;
		$tardy->school_id = $request->school_id;
		$tardy->semester_id = $semester->uuid;
		$tardy->period_id = $request->period_id;
		$tardy->student_id = $request->student_id;
		$tardy->is_excuse = 'false';
		$save_tardy = $tardy->save();

		if ($save_tardy) {
			return $this->sendResponse("Tardy created successfully");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function getPeriodTardy(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'period_id' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$get_tardy = Tardy::with('School', 'Semester', 'Period')->where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'period_id'=>$request->period_id])->get();

		if (sizeof($get_tardy) > 0) {
			$all_tardy = [];
			foreach ($get_tardy as $tardy) {
				$student = StudentData::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$tardy->student_id])->first();
				$StudentSchedules = StudentSchedules::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$tardy->student_id])->get();
				$StudentContacts = StudentContacts::where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid, 'student_id'=>$tardy->student_id])->get();
				$student['student_schedules'] = $StudentSchedules;
				$student['student_contacts'] = $StudentContacts;

				$tardy['student_info'] = $student;
				$all_tardy[] = $tardy;
			}

			return $this->sendResponse($all_tardy);
		}else{
			return $this->sendResponse("Sorry, Tardy not found!", 200, false);
		}
	}

	public function tardyChartData(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
		]);

		$tardy_array = [];

	    $i = 0;
	    while ($i < 8) {
	        $today = Carbon::today();
	        $date = $today->subDays($i)->format('Y-m-d');

	        $semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

	        $tardy = Tardy::where('created_at', 'like', '%'.$date.'%')->where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->count();

	        $tardy_count = ['date'=>$date, 'tardy_count'=>$tardy];
	        array_push($tardy_array, $tardy_count);
	        $i++;
	    }

	    return $this->sendResponse($tardy_array);
	}

	public function updateTardyExcuse(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'tardy_id' => 'required',
			'is_excuse' => 'required|in:true,false',
		]);

		$update = Tardy::where(['school_id'=>$request->school_id, 'uuid'=>$request->tardy_id])->update(['is_excuse'=>$request->is_excuse]);

		if ($update) {
			return $this->sendResponse('Tardy updated successfully!');
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function createDetention(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'student_id' => 'required',
			'reason_id' => 'required',
			'create_date' => 'nullable',
			'comment' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$time = strtotime(Carbon::now());
		$uuid = "det".$time.rand(10,99)*rand(10,99);

		$detention = new Detentions;
		$detention->uuid = $uuid;
		$detention->school_id = $request->school_id;
		$detention->semester_id = $semester->uuid;
		$detention->student_id = $request->student_id;
		$detention->reason_id = $request->reason_id;
		$detention->create_date = $request->create_date;
		$detention->comment = $request->comment;
		$detention->serverd = 'false';
		$save_detentions = $detention->save();

		if ($save_detentions) {
			return $this->sendResponse("Detention added successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function getDetentions(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
		]);

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$detentions = Detentions::with('School', 'Semester', 'StudentData', 'Reason')->where(['school_id'=>$request->school_id, 'semester_id'=>$semester->uuid])->get();

		if (sizeof($detentions) > 0) {
			return $this->sendResponse($detentions);
		}else{
			return $this->sendResponse("Sorry, Data not found!", 200, false);
		}
	}

	public function updateDetentionServe(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'detention_id' => 'required',
			'serverd' => 'required|in:true,false',
		]);

		$update = Detentions::where(['school_id'=>$request->school_id, 'uuid'=>$request->detention_id])->update(['serverd'=>$request->serverd]);

		if ($update) {
			return $this->sendResponse("Detention updated successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}
}
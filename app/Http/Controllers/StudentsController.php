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
			'phone' => 'required',
			'phone_type' => 'required',
			'email' => 'required',
		]);

		$result = 0;

		$file = $request->file;

		$studentArr = $this->csvToArray($file);
		
		$exist_flag = 0;

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		foreach ($studentArr as $student) {
			if (!isset($student[$request->student_id]) || !isset($student[$request->name]) || !isset($student[$request->phone]) || !isset($student[$request->phone_type]) || !isset($student[$request->email])) {
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
			'grade' => 'required',
			'dbo' => 'required',
			'counselor' => 'required',
			'locker_number' => 'required',
			'locker_combination' => 'required',
			'parking_space' => 'required',
			'license_plate' => 'required',
		]);

		$result = 0;

		$file = $request->file;

		$studentArr = $this->csvToArray($file);
		
		$exist_flag = 0;

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		foreach ($studentArr as $student) {
			if (!isset($student[$request->first_name]) || !isset($student[$request->first_name]) || !isset($student[$request->first_name]) || !isset($student[$request->first_name]) || !isset($student['Date of Birth']) || !isset($student['Counselor']) || !isset($student['Locker Number']) || !isset($student['Locker Combination']) || !isset($student['Parking Space']) || !isset($student['License Plate'])) {
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
			'teacher' => 'required',
			'room_number' => 'required',
			'class_name' => 'required',
			'semester' => 'required',
		]);

		$result = 0;

		$file = $request->file;

		$studentArr = $this->csvToArray($file);
		
		$exist_flag = 0;

		$semester = Semesters::where('school_id', $request->school_id)->orderBy('created_at', 'desc')->first();

		$periods = [];
		foreach ($studentArr as $student) {
			$periods[] = $student['Period'];
			if (!isset($student[$request->student_id]) || !isset($student[$request->period]) || !isset($student[$request->teacher]) || !isset($student[$request->room_number]) || !isset($student[$request->class_name]) || !isset($student[$request->semester])) {
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
			$uuid = "per".$time.rand(10,99)*rand(10,99);

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

		$result = StudentContacts::where('school_id', $request->school_id)->get();
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

		$result = StudentData::where('school_id', $request->school_id)->get();
		if (sizeof($result) > 0) {
			return $this->sendResponse($result);
		}else{
			return $this->sendResponse("Sorry, Student data not found!",200,false);
		}
	}

	public function getStudentSchedules(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
		]);

		$result = StudentSchedules::where('school_id', $request->school_id)->get();
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
}
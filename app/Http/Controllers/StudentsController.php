<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentContacts;
use App\Models\StudentData;
use App\Models\StudentSchedules;
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

		foreach ($studentArr as $student) {
			if (!isset($student[$request->student_id]) || !isset($student[$request->name]) || !isset($student[$request->phone]) || !isset($student[$request->phone_type]) || !isset($student[$request->email])) {
				return $this->sendResponse("Data is not formatted in this file!",200,false);
			}

			$exist_flag = 1;

			$student_contact = new StudentContacts;
			$student_contact->school_id = $request->school_id;
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

		foreach ($studentArr as $student) {
			if (!isset($student[$request->first_name]) || !isset($student[$request->first_name]) || !isset($student[$request->first_name]) || !isset($student[$request->first_name]) || !isset($student['Date of Birth']) || !isset($student['Counselor']) || !isset($student['Locker Number']) || !isset($student['Locker Combination']) || !isset($student['Parking Space']) || !isset($student['License Plate'])) {
				return $this->sendResponse("Data is not formatted in this file!",200,false);
			}

			$exist_flag = 1;

			$student_data = new StudentData;
			$student_data->school_id = $request->school_id;
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

		foreach ($studentArr as $student) {
			if (!isset($student[$request->student_id]) || !isset($student[$request->period]) || !isset($student[$request->teacher]) || !isset($student[$request->room_number]) || !isset($student[$request->class_name]) || !isset($student[$request->semester])) {
				return $this->sendResponse("Data is not formatted in this file!",200,false);
			}
			
			$exist_flag = 1;

			$student_schedule = new StudentSchedules;
			$student_schedule->school_id = $request->school_id;
			$student_schedule->student_id = $student['Student ID'];
			$student_schedule->period = $student['Period'];
			$student_schedule->teacher = $student['Teacher'];
			$student_schedule->room_number = $student['Room Number'];
			$student_schedule->class_name = $student['Class Name'];
			$student_schedule->semester = $student['Semester'];
			$result = $student_schedule->save();
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

		$student = StudentData::with('StudentSchedules', 'StudentContacts')->where(['school_id'=>$request->school_id, 'student_id'=>$request->student_id])->first();
		if (!empty($student)) {
			return $this->sendResponse($student);
		}else{
			return $this->sendResponse("Sorry, Student not found!",200,false);
		}
	}
}
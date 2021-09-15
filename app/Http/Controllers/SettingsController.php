<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use Carbon\Carbon;

class SettingsController extends Controller
{
	public function updateTardySetting(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'setting_name' => 'required',
			'setting_value' => 'required',
			'setting_status' => 'required|in:E,D',
		]);
		
		$update_setting = Settings::where(['school_id'=>$request->school_id, 'name'=>$request->setting_name])->update(['value'=>$request->setting_value, 'status'=>$request->setting_status]);

		if ($update_setting) {
			return $this->sendResponse("Setting updated successfully!");
		}else{
			return $this->sendResponse("Sorry, Something went wrong!", 200, false);
		}
	}

	public function getSetting(Request $request){
		$this->validate($request, [
			'school_id' => 'required',
			'setting_name' => 'required',
		]);

		$setting = Settings::where(['school_id'=>$request->school_id, 'name'=>$request->setting_name])->first();

		if (!empty($setting)) {
			return $this->sendResponse($setting);
		}else{
			return $this->sendResponse("Setting not found!", 200, false);
		}
	}
}
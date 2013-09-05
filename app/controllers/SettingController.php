<?php

class SettingController extends BaseController 
{

	public function index()
	{
		$user = Sentry::getUser();
		$sitename = Setting::first()->pluck('sitename');

		return View::make('dashboard.settings', array('user' => $user, 'sitename' => $sitename));

	}

	public function settings()
	{
		$user = Sentry::getUser();

		if(Input::get('email') == $user->email)
		{
		    $rules = array(
		    		'first_name' => 'required|alpha_num|max:128', 
		    		'last_name' => 'required|alpha_num|max:128', 
		    		'email' => 'required|email|max:255',
		    		'password' => 'required|min:7|confirmed',
		    		'sitename' => 'required|max:50'
		    	);
		}

	    else
	    {
		    $rules = array(
		    		'first_name' => 'required|alpha_num|max:128', 
		    		'last_name' => 'required|alpha_num|max:128', 
		    		'email' => 'required|email|max:255|unique:users',
		    		'password' => 'required|min:7|confirmed',
		    		'sitename' => 'required|max:50'
		    	);
	    }

	    $validator = Validator::make(Input::all(), $rules);

	    if ($validator->fails())
	    {
	    	return Response::json($validator->messages());
	    }

	    else
	    {
	    	$setting = Setting::first();
	    	$setting->sitename = Input::get('sitename');
	    	$setting->save();

	    	$user = User::find($user->id);
	    	$user->first_name = Input::get('first_name');
	    	$user->last_name = Input::get('last_name');
	    	$user->email = Input::get('email');
	    	$user->password = Input::get('password');
	    	$user->save();

			$feedback = array('success'=>'Settings successfully saved.');
	    	return Response::json($feedback);
	    }

	}

}

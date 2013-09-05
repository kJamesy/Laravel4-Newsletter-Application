<?php

class HelpController extends BaseController 
{

	public function index()
	{
		$user = Sentry::getUser();	
		$sitename = Setting::first()->pluck('sitename');

		return View::make('dashboard.help', array('user' => $user, 'sitename' => $sitename));		
	}	

	public function do_help()
	{
		$user = Sentry::getUser();
		$subject = "Newsletter Help: " . Input::get('subject');
		$emailbody = Input::get('message');

	    $from_name = $user->first_name . ' ' . $user->last_name;
	    $from_email = $user->email;

	    $admin = User::first();
	    $to_name = $admin->first_name . ' ' . $admin->last_name;
	    $to_email = $admin->email;

		$rules = array(
			'subject' => 'required|max:128',
			'message' => 'required'
		);	

	    $validator = Validator::make(Input::all(), $rules);

	    if ($validator->fails())
	    {
	    	return Redirect::to('dashboard/help')->withErrors($validator);
	    }  

	    else
	    {
			$browser = new Browser;
			$userbrowser = $browser->getBrowser() . ' ' . $browser->getVersion();
			$userplatform = $browser->getPlatform();
			$userIP = $_SERVER["REMOTE_ADDR"];
			$page = URL::current();

			$data = array('emailbody' => $emailbody, 'userbrowser' => $userbrowser, 'userplatform' => $userplatform, 'userIP' => $userIP, 'page' => $page);

			$issent = 

			Mail::send('emails.help-email', $data, function($message) use ($from_email, $from_name, $subject, $to_name, $to_email)
			{
			    $message->from($from_email, $from_name)->to($to_email, $to_name)->subject($subject);
			});	  

			if ($issent)
		    	return Redirect::to('dashboard/help')->with('success', 'Success! You will be contacted soon regarding your issue.');
			else
				return Redirect::to('dashboard/help')->with('error', 'An error was encountered sending the email. Please try again.');			  

	    }

	}


}
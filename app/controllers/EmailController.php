<?php

class EmailController extends BaseController 
{

	public function index()
	{
		$user = Sentry::getUser();
		$emails = Email::with('trackers.subscriber')->orderBy('id', 'desc')->where('deleted', '=', 0)->get();
		$trashes = Email::with('trackers.subscriber')->orderBy('id', 'desc')->where('deleted', '=', 1)->get();
		$sitename = Setting::first()->pluck('sitename');

		if(Input::get('email-content'))
			$content = Input::get('email-content');
		else
			$content = '';

		if(Input::get('email-subject'))
			$subject_content = Input::get('email-subject');
		else
			$subject_content = '';		

		return View::make('dashboard.emails', array('user' => $user, 'emails' => $emails, 'trashes' => $trashes, 'content' => $content, 'subject_content' => $subject_content, 'sitename' => $sitename));		
	}	

	public function send_email($dummy)
	{
		$rules = array(
			'from_name' => 'required|max:128',
			'from_email' => 'required|email|max:255',
			'subject' => 'required|max:128',
			'emailbody' => 'required'
		);	

	    $validator = Validator::make(Input::all(), $rules);

	    if ($validator->fails())
	    {
	    	return Response::json(array('validation' => $validator->messages()->toArray()));
	    }   

	    else
	    {
	    	$from_name = Input::get('from_name');
	    	$from_email = Input::get('from_email');
	    	$selected = Input::get('to');
	    	$subject = Input::get('subject');
	    	$emailbody = Input::get('emailbody');

	    	$from = $from_name . ' (' . $from_email . ')';

	    	$recipients = Subscriber::whereIn('email', $selected)->where('active', '=', 1)->get(); 

	    	$email = new Email;
	    	$email->from = $from;
	    	$email->subject = $subject;
	    	$email->message = $emailbody;
	    	$email->save();

	    	$email_id = $email->id;
	    	$numrecipients = $recipients->count();
	    	$numsent = 0;

	    	foreach ($recipients as $key => $recipient) 
	    	{
	    		$tracker = new Tracker;
	    		$tracker->subscriber_id = $recipient->id;
	    		$tracker->email_id = $email_id;
	    		$tracker->save();

	    		$tracker_id = $tracker->id;
	    		$tracker_url = URL::to('tracker/'.$tracker_id);
	    		$unsubscriber_url = URL::to('unsubscribe/'.$tracker_id);
	    		$subscriber = $recipient;

	    		$data = array('emailbody' => $emailbody, 'tracker' => $tracker_url, 'unsubscribe' => $unsubscriber_url, 'subscriber' => $subscriber);
	    		$to_email = $subscriber->email;
	    		$to_name = $subscriber->first_name . ' ' . $subscriber->last_name;

				$issent = 

				Mail::send('emails.sub-emails', $data, function($message) use ($from_email, $from_name, $to_email, $to_name, $subject)
				{
				    $message->from($from_email, $from_name)->to($to_email, $to_name)->subject($subject);
				});	    

				if($issent)
				{
					$numsent += 1;
				}	

				else
				{
					$tracker->bounced = 1;
					$tracker->save();
				}	
	    	}

	    	if ($numsent == $numrecipients)
	    		return Response::json(array('success' => 'Your email was successfully sent to <b>' . $numsent . '</b> subscribers out of the ' . $numrecipients .' subscribers you selected. <b>Rejoice!</b>'));
	    	else
	    		return Response::json(array('success' => 'Your email was successfully sent to <b>' . $numsent . '</b> subscribers out of the ' . $numrecipients . 'All bounces have been logged.'));	
	  	}

	}

	public function trash_email($dummy)
	{
		$selected = Input::get('selected');

		$all = count($selected);
		$num = 0;

		foreach ($selected as $key => $id) 
		{
			$email = Email::find($id);
			$email->deleted = 1;
			if ($email->save())
				$num += 1;
		}

		return Response::json(array('success' => $num . ' out of the selected ' . $all . ' emails have been successfully deleted.'));
	}

	public function destroy_email($dummy)
	{
		$selected = Input::get('selected');

		$email = Email::whereIn('id', $selected)->delete();

		return Response::json(array('success' => 'The selected emails have been successfully destroyed.'));
	}

	public function move_to_sent($id)
	{
		$email = Email::find($id);
		$email->deleted = 0;
		$email->save();

		return Redirect::to('dashboard/emails');
	}


}
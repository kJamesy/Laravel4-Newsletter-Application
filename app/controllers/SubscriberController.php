<?php

class SubscriberController extends BaseController 
{
	public function subscribers()
	{
		$user = Sentry::getUser();
		$subscribers = Subscriber::orderBy('first_name', 'asc')->get();
		$sitename = Setting::first()->pluck('sitename');

		return View::make('dashboard.subscribers', array('user' => $user, 'subscribers' => $subscribers, 'sitename' => $sitename));
	}

	public function add_new_subscriber()
	{
	    $rules = array('first_name' => 'required|alpha_num|max:128', 'last_name' => 'required|alpha_num|max:128', 'email' => 'required|email|max:255|unique:subscribers');

	    $validator = Validator::make(Input::all(), $rules);

	    if ($validator->fails())
	    {
	    	return Response::json($validator->messages());
	    }	

	    else 
	    {
	    	$first_name = Input::get('first_name');
	    	$last_name = Input::get('last_name');
	    	$email = Input::get('email');

	    	$subscriber = new Subscriber;
	    	$subscriber->first_name = $first_name;
	    	$subscriber->last_name = $last_name;
	    	$subscriber->email = $email;
	    	$subscriber->active = 1;
	    	$subscriber->save();

	    	$list = Addressbook::find(1);

			$list->subscribers()->attach($subscriber->id);
			$list->save();

	    	$feedback = array('success'=>'New subscriber successfully saved');
	    	return Response::json($feedback);
	    }	
	}

	public function get_subscriber($id)
	{
		$subscriber = Subscriber::find($id);
		return Response::json($subscriber);
	}

	public function update_subscriber($id)
	{
		$subscriber = Subscriber::find($id);

		if ($subscriber->email == Input::get('email'))
		{
			$rules = array('first_name' => 'required|alpha_num|max:128', 'last_name' => 'required|alpha_num|max:128');
		}

		else
		{
			$rules = array('first_name' => 'required|alpha_num|max:128', 'last_name' => 'required|alpha_num|max:128', 'email' => 'required|email|max:255|unique:subscribers');
		}
	    

	    $validator = Validator::make(Input::all(), $rules);

	    if ($validator->fails())
	    {
	    	return Response::json($validator->messages());
	    }	

	    else
	    {
	    	$subscriber->first_name = Input::get('first_name');
	    	$subscriber->last_name = Input::get('last_name');
	    	$subscriber->email = Input::get('email');
	    	$subscriber->active = Input::get('active');
	    	$subscriber->save();

	    	$success = array('success' => 'Subscriber successfully updated.');
	    	return Response::json($success);
	    }

	}

	public function delete_subscribers($dummy)
	{
		$selected = Input::get('selected');
		$num_del = 0;

		foreach ($selected as $key => $id) 
		{
			$subscriber = Subscriber::find($id);	

			if ($subscriber->delete())
				$num_del += 1;	
		}

		$message = $num_del . ' subscribers have been successfully deleted.';
		return Response::json(array('success' => $message));
	}

	public function import_csv($num)
	{
		$input = Input::file('csvfile');
		$ext = pathinfo($input->getClientOriginalName(), PATHINFO_EXTENSION);

	    $rules = array('csvfile' => 'required');
	    $validator = Validator::make(Input::all(), $rules);

	    if ($validator->fails())
	    {
			$error =  array('files' => array(array(
				'error' => $validator->messages()->first(),
				'name' => 'none', 
				'size' => 'none', 
				'url' => 'none', 
				'thumbnail_url' => 'none', 
				'delete_url' => 'none', 
				'delete_type' => 'DELETE')));

	        return Response::json($error);
	    }

	    elseif ($ext != 'csv')
	    {
			$error =  array('files' => array(array(
				'error' => 'Only CSV files are allowed',
				'name' => 'none', 
				'size' => 'none', 
				'url' => 'none', 
				'thumbnail_url' => 'none', 
				'delete_url' => 'none', 
				'delete_type' => 'DELETE')));

	        return Response::json($error);	    	
	    }

	    else
	    {
	    	$destination = 'imports'; 
	    	$filename = date('Y_m_d_H_i').'_subscribers.'.$ext; 
	    	$filepath = asset('imports/'.$filename);

		    if ($input->move($destination, $filename))
		    {

				$thecsv = Readcsv::csv_open($filepath);

				$rules = array(
					'first_name' => 'required|alpha_num|max:128',
					'last_name' => 'required|alpha_num|max:128',
					'email' => 'required|email|max:255|unique:subscribers'
				);	

				$length = count($thecsv);

				for($i=0; $i<$length; $i++)
				{
					$validator = Validator::make($thecsv[$i], $rules);		
					if ( $validator->fails() )
					{
						// var_dump($validator->messages());
						unset($thecsv[$i]);
					}	

				}

				$newcsvarray = array_values($thecsv);
				$newlength = count($newcsvarray);

				$timestamp = new Datetime;

				for($i=0; $i<$newlength; $i++)
				{
					$newcsvarray[$i]['active'] = 1;
					$newcsvarray[$i]['created_at'] = $timestamp;
					$newcsvarray[$i]['updated_at'] = $timestamp;
				}


				if (count($newcsvarray) > 0)
				{
					Subscriber::insert($newcsvarray);
					$list = Addressbook::find(1);
					$subscribers = Subscriber::get();

					foreach ($subscribers as $key => $subscriber) 
					{
						$list->subscribers()->attach($subscriber->id);
						// $list->save();
					}
					
					$subscribers = Subscriber::where('created_at', '=', $timestamp)->count();

					$success =  array('files' => array(array(
						'success' => $subscribers . ' out of ' . $length . ' subscribers were succesfully imported and saved.',
						'name' => $filename, 
						'size' => 'none', 
						'url' => $filepath, 
						'thumbnail_url' => 'none', 
						'delete_url' => 'none', 
						'delete_type' => 'DELETE')));

					return Response::json($success);	
				}
				
				else
				{
					$error =  array('files' => array(array(
						'error' => 'None of the subscribers has been imported. Kindly try again.',
						'name' => 'none', 
						'size' => 'none', 
						'url' => 'none', 
						'thumbnail_url' => 'none', 
						'delete_url' => 'none', 
						'delete_type' => 'DELETE')));

			        return Response::json($error);
				}
		    }

		    else
		    {
		        return Response::json(array('error' => "An error was encountered. Kindly refresh and try again."));
		    }	
	    }	
	}

    public function export_csv($num)
    {
        $subscribers = Subscriber::get();

        if ($num == 1)
        {
	        $file_name = date('Y_m_d_H_i').'_subscribers'.'.csv';
		    $file = fopen('exports/'.$file_name, 'w');

		    $field_names = false;

		    foreach ($subscribers as $row) 
		    {
		    	if (!$field_names)
		    	{
		    		fputcsv($file, array_keys($row->toArray()));
		    		$field_names = true;
		    	}

		        fputcsv($file, $row->toArray());
		    }

		    fclose($file);

		    $path = asset('exports/'.$file_name);

		    return Response::json(array('file' => $path));        	
        }

	}

	public function get_subscribers($dummy)
	{
		$selected_subs = Subscriber::whereIn('id', Input::get('subsarray'))->get();

		return Response::json($selected_subs);	
	}


	public function sub_send_email($dummy)
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
	    	$from = $from_name . ' (' . $from_email . ')';
	    	$recipients = Input::get('to');
	    	$subject = Input::get('subject');
	    	$emailbody = Input::get('emailbody');

	    	$email = new Email;
	    	$email->from = $from;
	    	$email->subject = $subject;
	    	$email->message = $emailbody;
	    	$email->save();

	    	$email_id = $email->id;
	    	$numrecipients = count($recipients);
	    	$numsent = 0;

	    	foreach ($recipients as $key => $recipient) 
	    	{
	    		$tracker = new Tracker;
	    		$tracker->subscriber_id = $recipient;
	    		$tracker->email_id = $email_id;
	    		$tracker->save();

	    		$tracker_id = $tracker->id;
	    		$tracker_url = URL::to('tracker/'.$tracker_id);
	    		$unsubscriber_url = URL::to('unsubscribe/'.$tracker_id);
	    		$subscriber = Subscriber::find($recipient);

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
	    		return Response::json(array('success' => 'Your email was successfully sent to <b>' . $numsent . '</b> subscribers out of the ' . $numrecipients .' you selected. <b>Rejoice!</b>'));
	    	else
	    		return Response::json(array('success' => 'Your email was successfully sent to <b>' . $numsent . '</b> subscribers out of the ' . $numrecipients . 'All bounces have been logged.'));

	    } 
	
	}	

	public function tracker($id)
	{
		$browser = new Browser;
		$userbrowser = $browser->getBrowser() . ' ' . $browser->getVersion();
		$userplatform = $browser->getPlatform();
		$userIP = $_SERVER["REMOTE_ADDR"];	

		$tracker = Tracker::find($id);

		$tracker->IP_address = $userIP;
		$tracker->browser = $userbrowser;
		$tracker->platform = $userplatform;
		$tracker->read = 1;
		$tracker->read_at = new Datetime;
		$tracker->save();

		$im=imagecreate(1,1);
	  	$white=imagecolorallocate($im,255,255,255);
	  	imagesetpixel($im,1,1,$white);

	  	header("content-type:image/jpg");
	   	imagejpeg($im);
		imagedestroy($im);
	}

	public function unsubscribe($id)
	{
		$browser = new Browser;
		$userbrowser = $browser->getBrowser() . ' ' . $browser->getVersion();
		$userplatform = $browser->getPlatform();
		$userIP = $_SERVER["REMOTE_ADDR"];

		$tracker = Tracker::find($id);
		$tracker->unsubscribed = 1;

		if ($tracker->IP_address == '')
			$tracker->IP_address = $userIP;

		if ($tracker->browser == '')
			$tracker->browser = $userbrowser;

		if ($tracker->platform == '')
			$tracker->platform = $userplatform;

		if ($tracker->read == 0)
			$tracker->read = 1;		

		if ($tracker->read_at == '0000-00-00 00:00:00')
			$tracker->read_at = new Datetime;

		$tracker->save();

		$sub_id = $tracker->subscriber_id;

		$subscriber = Subscriber::find($sub_id);
		$subscriber->active = 0;
		$subscriber->save();

		echo "You have been successfully unsubscribed.";
	}


	public function lists()
	{
		$user = Sentry::getUser();
		$lists = Addressbook::with('subscribers')->orderBy('id', 'desc')->get();

		return View::make('dashboard.lists', array('user' => $user, 'lists' => $lists));		
	}

}
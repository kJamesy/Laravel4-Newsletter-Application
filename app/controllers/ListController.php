<?php

class ListController extends BaseController 
{

	public function index()
	{
		$user = Sentry::getUser();
		$lists = Addressbook::with(array('subscribers' => function($query)
								{
									$query->orderBy('first_name', 'asc');
								}))->orderBy('id', 'asc')->get();
		$sitename = Setting::first()->pluck('sitename');

		return View::make('dashboard.lists', array('user' => $user, 'lists' => $lists, 'sitename' => $sitename));		
	}	

	public function add_new_list()
	{
		$rules = array('name' => 'required|max:128|unique:lists', 'active' => 'required');
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
			return Response::json($validator->messages());
		else
		{
			$name = Input::get('name');
			$active = Input::get('active');

			$list = new Addressbook;
			$list->name = $name;
			$list->active = $active;
			$list->save();

			return Response::json(array('success' => 'New list successfully saved' ));
		}
	}

	public function get_list($id)
	{
		$list = Addressbook::find($id);
		return Response::json($list);
	}

	public function update_list($id)
	{
		$list = Addressbook::with(array('subscribers' => function($query)
							{
								$query->orderBy('first_name', 'asc');
							}))->find($id);
		$activebefore = $list->active;

		if($list->subscribers)
			$subscribers = $list->subscribers;

		$name = Input::get('name');
		$active = Input::get('active');		

		if ($list->name == $name)
		{
			$rules = array('name' => 'required|max:128', 'active' => 'required');
		}

		else
		{
			$rules = array('name' => 'required|max:128|unique:lists', 'active' => 'required');
		}

	    $validator = Validator::make(Input::all(), $rules);

	    if ($validator->fails())
	    {
	    	return Response::json($validator->messages());
	    }	

	    else
	    {
			$list->name = $name;
			$list->active = $active;
			$list->save();

			if($subscribers)
			{
				$activeafter = $list->active;

				if($activebefore == 1 && $activeafter == 0)
				{
					foreach ($subscribers as $key => $value) 
					{
						$subscriber = Subscriber::find($value->id);
						$subscriber->active = 0;
						$subscriber->save();
					}
				}

				if($activebefore == 0 && $activeafter == 1)
				{
					foreach ($subscribers as $key => $value) 
					{
						$subscriber = Subscriber::with('lists')->find($value->id);
						$check = 1;

						foreach($subscriber->lists as $alist) //Subscriber must belong to at least one list otherwise we are trying to get property of a non-object
						{
							if($alist->active == 0)
								$check = 0;
						}

						if($check == 1)
						{
							$subscriber->active = 1;
						}

						$subscriber->save();
					}				
				}
			}

			return Response::json(array('success' => 'List successfully updated' ));
	    }

	}

	public function delete_list($id)
	{
		$list = Addressbook::find($id);

		if ($list->delete())
			return Response::json(array('success' => 'The selected list has been successfully deleted'));
		else
			return Response::json(array('error' => 'An error occured. Kindly try again or contact admin.'));
	}

	public function select_subscribers_to_add($id)
	{
		$list = Addressbook::with('subscribers')->find($id);
		$subscribers = '';

		if ($list->subscribers->count() > 0)
		{
			$subs_array = array();

			foreach ($list->subscribers as $list_subscriber) 
			{
				$subs_array[] = $list_subscriber->id;
			}

			$subscribers = Subscriber::whereNotIn('id', $subs_array)->orderBy('first_name', 'asc')->get();
		}

		else
		{
			$subscribers = Subscriber::orderBy('first_name', 'asc')->get();
		}


		return Response::json($subscribers);
	}


	public function add_subscribers_to_list($id)
	{
		$subs_array = Input::get('subsarray');
		$list = Addressbook::find($id);
		$count = 0;

		foreach ($subs_array as $key => $value) 
		{
			$list->subscribers()->attach($value);

			if($list->active == 0)
			{
				$subscriber = Subscriber::find($value);
				$subscriber->active = 0;
				$subscriber->save();
			}

			else if($list->active == 1)
			{
				$subscriber = Subscriber::with('lists')->find($value);
				$check = 1;

				foreach($subscriber->lists as $alist) //Subscriber must belong to at least one list otherwise we are trying to get property of a non-object
				{
					if($alist->active == 0)
						$check = 0;
				}

				if($check == 1)
				{
					$subscriber->active = 1;
				}

				else
				{
					$subscriber->active = 0;
				}

				$subscriber->save();
			}

			$count++;
		}

		return Response::json(array('success' => $count . ' subscribers successfully added to ' . $list->name));
	}

	public function get_subscribers_to_remove($id)
	{
		$list = Addressbook::with(array('subscribers' => function($query){ $query->orderBy('first_name', 'asc'); }))->find($id);
		return Response::json($list->subscribers);
	}

	public function remove_subscribers_from_list($id)
	{
		$subs_array = Input::get('subsarray');
		$list = Addressbook::find($id);
		$count = 0;

		foreach($subs_array as $key => $value) 
		{
			$list->subscribers()->detach($value);

			if($list->active == 0)
			{
				$subscriber = Subscriber::with('lists')->find($value);
				$check = 1;

				foreach($subscriber->lists as $alist) 
				{
					if($alist->active == 0)
						$check = 0;
				}

				if($check == 1)
				{
					$subscriber->active = 1;
					$subscriber->save();
				}
			}

			$count++;
		}

		return Response::json(array('success' => $count . ' subscribers successfully removed from ' . $list->name));

	}


    public function export_list_csv($id)
    {
        $list = Addressbook::with(array('subscribers' => function($query){ $query->orderBy('first_name', 'asc'); }))->find($id);

        $file_name = date('Y_m_d_H_i').'_'.$list->name.'_list'.'.csv';
	    $file = fopen('exports/'.$file_name, 'w');

	    $field_names = false;

	    foreach ($list->subscribers as $row) 
	    {
	    	if (!$field_names)
	    	{
	    		$allkeys = array_keys($row->toArray());
	    		$trimmedkeys = array_slice($allkeys, 0, 7);

	    		fputcsv($file, $trimmedkeys);
	    		$field_names = true;
	    	}

	    	$allvalues = array_values($row->toArray());
	    	$trimmedvalues = array_slice($allvalues, 0, 7);

	        fputcsv($file, $trimmedvalues);
	    }

	    fclose($file);

	    $path = 'exports/'.$file_name;

	    return Redirect::to($path); //Response::json(array('file' => $path));        	
	}

	public function fetch_subs($dummy)
	{
		$lists = Input::get('listsarray');		

		$subscribers = Subscriber::join('list_subscriber', 'list_subscriber.subscriber_id', '=', 'subscribers.id')
		    ->whereIn('list_subscriber.list_id', $lists)
		    ->groupBy('list_subscriber.subscriber_id')
		    ->where('subscribers.active', '=', 1)
		    ->orderBy('subscribers.first_name', 'asc')
		    ->get(array('subscribers.*'));

		 return Response::json($subscribers);

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
			$recipients = Subscriber::join('list_subscriber', 'list_subscriber.subscriber_id', '=', 'subscribers.id')
			    ->whereIn('list_subscriber.list_id', Input::get('to'))
			    ->groupBy('list_subscriber.subscriber_id')
			    ->where('subscribers.active', '=', 1)
			    ->get(array('subscribers.*'));

	    	$subject = Input::get('subject');
	    	$emailbody = Input::get('emailbody');

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
	    		return Response::json(array('success' => 'Your email was successfully sent to <b>' . $numsent . '</b> subscribers out of the ' . $numrecipients .' you selected. <b>Rejoice!</b>'));
	    	else
	    		return Response::json(array('success' => 'Your email was successfully sent to <b>' . $numsent . '</b> subscribers out of the ' . $numrecipients . 'All bounces have been logged.'));

	    } 
	
	}	

}
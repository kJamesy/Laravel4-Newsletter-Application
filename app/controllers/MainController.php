<?php

class MainController extends BaseController 
{

	public function index()
	{
		$minutes = 30;

		$user = Cache::remember('user', $minutes, function()
		{
		    return Sentry::getUser();
		});				

		$sitename = Cache::remember('sitename', $minutes, function()
		{
		    return Setting::first()->pluck('sitename');
		});			

		$subscribers = Cache::remember('subscribers', $minutes, function()
		{
		    return Subscriber::orderBy('id', 'desc')->get();
		});	

		$active_subs = Cache::remember('active_subs', $minutes, function()
		{
		    return Subscriber::where('active', '=', 1)->count();
		});				

		$inactive_subs = Cache::remember('inactive_subs', $minutes, function()
		{
		    return Subscriber::where('active', '=', 0)->count();
		});	

		$percent_active = 0;
		$percent_inactive = 0;

		if($subscribers->count() > 0)
		{
			$percent_active = round(($active_subs/$subscribers->count())*100);
			$percent_inactive = round(($inactive_subs/$subscribers->count())*100);
		}



		$emails_num = Cache::remember('emails_num', $minutes, function()
		{
		    return Email::with('trackers')->count();
		});	

		$email_impressions = Cache::remember('email_impressions', $minutes, function()
		{
		    return Tracker::count();
		});					

		$read_emails = Cache::remember('read_emails', $minutes, function()
		{
		    return Tracker::where('read', '=', 1)->count();
		});	

		$unread_emails = Cache::remember('unread_emails', $minutes, function()
		{
		    return Tracker::where('read', '=', 0)->count();
		});	

		$bounced_emails = Cache::remember('bounced_emails', $minutes, function()
		{
		    return Tracker::where('bounced', '=', 1)->count();
		});

		$unsubscribed_emails = Cache::remember('unsubscribed_emails', $minutes, function()
		{
		    return Tracker::where('unsubscribed', '=', 1)->count();
		});
		
		$percent_read = 0;
		$percent_unread = 0;
		$percent_bounced = 0;
		$percent_unsubscribed = 0;

		if ($email_impressions > 0) 
		{
			$percent_read = round(($read_emails/$email_impressions)*100);
			$percent_unread = round(($unread_emails/$email_impressions)*100);
			$percent_bounced = round(($bounced_emails/$email_impressions)*100);
			$percent_unsubscribed = round(($unsubscribed_emails/$email_impressions)*100);
		}



		$browser_outof = Cache::remember('browser_outof', $minutes, function()
		{
		    return 	DB::table('trackers') 
						->select('browser', DB::raw('count(*) as total'))
						->groupBy('browser')
						->where('read', '=', 1)
						->orderBy('total', 'desc')
						->get();
		});		

		$browsers_emails = Cache::remember('browsers_emails', $minutes, function()
		{
		    return 	DB::table('trackers') 
							->select('browser', DB::raw('count(*) as total'))
							->groupBy('browser')
							->where('read', '=', 1)
							->orderBy('total', 'desc')
							->take(5)
							->get();
		});	
	
		$platform_outof = Cache::remember('platform_outof', $minutes, function()
		{
		    return 	DB::table('trackers') 
							->select('platform', DB::raw('count(*) as total'))
							->groupBy('platform')
							->where('read', '=', 1)
							->orderBy('total', 'desc')
							->get();
		});	
	
		$platforms_emails = Cache::remember('platform_emails', $minutes, function()
		{
		    return 	DB::table('trackers') 
							->select('platform', DB::raw('count(*) as total'))
							->groupBy('platform')
							->where('read', '=', 1)
							->orderBy('total', 'desc')
							->take(5)
							->get();
		});	
		
	

		$percent_browsers = [];					
		$percent_platforms = [];		

		if($read_emails > 0)
		{
			foreach ($browsers_emails as $key => $browser) 
			{
				$percent_browsers[] = round(($browser->total/$read_emails)*100);
			}

			foreach ($platforms_emails as $key => $platform) 
			{
				$percent_platforms[] = round(($platform->total/$read_emails)*100);
			}	
		}	



		$subs_unsubscribed = Cache::remember('subs_unsubscribed', $minutes, function()
		{
		    return Tracker::where('unsubscribed', '=', 1)->groupBy('subscriber_id')->get()->count();
		});

		$self_deactivated = 0;

		if($inactive_subs > 0)
		{
			$self_deactivated = round(($subs_unsubscribed/$inactive_subs)*100);
		}

		$admin_deactivated = 100 - $self_deactivated;

		return View::make('dashboard.index', array(
				  'user' => $user,
				  'sitename' => $sitename,
				  'emails_num' => $emails_num,
				  'impressions' => $email_impressions, 
				  'read_emails' => $percent_read,
				  'unread_emails' => $percent_unread,
				  'bounced_emails' => $percent_bounced,
				  'unsubscribed_emails' => $unsubscribed_emails,
				  'browsers_emails' => $browsers_emails,
				  'browser_outof' => count($browser_outof),
				  'browsers_array' => $percent_browsers,
				  'platforms_emails' => $platforms_emails,
				  'platform_outof' => count($platform_outof),
				  'platforms_array' => $percent_platforms,
				  'subscribers' => $subscribers, 
				  'active_subs' => $percent_active,
				  'inactive_subs' => $percent_inactive,
				  'inactive_outof' => $inactive_subs,
				  'self_deactivated' => $self_deactivated,
				  'admin_deactivated' => $admin_deactivated                                                                                                                                                                                                                                                                                                                                                                                                                          			  
			));
	}
}

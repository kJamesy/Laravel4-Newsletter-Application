<?php

Route::get('/', 'FrontendController@index');

Route::get('setup-form', function()
{
	return View::make('frontend.setup-form');
});

Route::post('setup', 'FrontendController@setup');

/*
|----------------------------------------------------------------------------
| AUTHENTICATION STUFF
|----------------------------------------------------------------------------
*/

	Route::group(array('before' => 'checkLoggedIn'), function()
	{
		Route::get('login-form', function()
		{
			return View::make('frontend.login-form');
		});	
			
		Route::post('login', 'FrontendController@login');
	});

	Route::get('password-request-form', function()
	{
		return View::make('frontend.password-request-form');
	});

	Route::post('password-request-form', 'FrontendController@send_reminder');

	Route::get('password-reset/{code}', function($reset_code)
	{
		return View::make('frontend.password-reset-form', array('code' => $reset_code));
	});

	Route::post('password-reset-form', 'FrontendController@do_reset_pass');

	Route::get('logout', 'FrontendController@logout');

/*
|----------------------------------------------------------------------------
| BACKEND
|----------------------------------------------------------------------------
*/

Route::group(array('before' => 'checkAuth'), function()
{
	Route::get('dashboard', 'MainController@index');

	/*
	|----------------------------------------------------------------------------
	| SUBSCRIBERS
	|----------------------------------------------------------------------------
	*/

	Route::get('dashboard/subscribers', 'SubscriberController@subscribers');

	Route::post('dashboard/subscribers/addnew', 'SubscriberController@add_new_subscriber');

	Route::post('dashboard/subscribers/fetch/{id}', 'SubscriberController@get_subscriber');

	Route::post('dashboard/subscribers/{id}', 'SubscriberController@update_subscriber');

	Route::post('dashboard/subscribers/delete/{id}', 'SubscriberController@delete_subscribers');

	Route::post('dashboard/subscribers/import/{num}', 'SubscriberController@import_csv');

	Route::post('dashboard/subscribers/export/{num}', 'SubscriberController@export_csv');

	Route::post('dashboard/subscribers/fetchall/{num}', 'SubscriberController@get_subscribers');

	Route::get('dashboard/subscribers/compose-form', function()
	{
		return View::make('dashboard.ajax.compose-email', array('user' => Sentry::getUser()));
	});

	Route::post('dashboard/subscribers/send-email/{num}', 'SubscriberController@sub_send_email');


	/*
	|----------------------------------------------------------------------------
	|LISTS
	|----------------------------------------------------------------------------
	*/

	Route::get('dashboard/lists', 'ListController@index');

	Route::post('dashboard/lists/addnew', 'ListController@add_new_list');

	Route::post('dashboard/lists/fetch/{id}', 'ListController@get_list');

	Route::post('dashboard/lists/{id}', 'ListController@update_list');

	Route::post('dashboard/lists/delete/{id}', 'ListController@delete_list');

	Route::post('dashboard/lists/fetch-subscribers/{id}', 'ListController@select_subscribers_to_add');

	Route::post('dashboard/lists/add-to-list/{id}', 'ListController@add_subscribers_to_list');

	Route::post('dashboard/lists/fetch-list-subs-remove/{id}', 'ListController@get_subscribers_to_remove');

	Route::post('dashboard/lists/remove-from-list/{id}', 'ListController@remove_subscribers_from_list');

	Route::get('dashboard/lists/export-list/{id}', 'ListController@export_list_csv');

	Route::get('dashboard/lists/compose-form', function()
	{
		return View::make('dashboard.ajax.compose-list-email', array('user' => Sentry::getUser()));
	});

	Route::post('dashboard/lists/fetch-subs/{num}', 'ListController@fetch_subs');

	Route::post('dashboard/lists/send-email/{num}', 'ListController@sub_send_email');


	/*
	|----------------------------------------------------------------------------
	|EMAILS
	|----------------------------------------------------------------------------
	*/

	Route::get('dashboard/emails', 'EmailController@index');

	Route::post('dashboard/emails', 'EmailController@index');

	Route::get('dashboard/emails/compose-email', function()
	{
		$subscribers = Subscriber::where('active', '=', 1)->get();
		return View::make('dashboard.ajax.compose-emails-email', array('user' => Sentry::getUser(), 'subscribers' => $subscribers));
	});

	Route::post('dashboard/emails/move-to-drafts/{num}', 'EmailController@move_to_drafts');

	Route::post('dashboard/emails/drafts-destroy/{num}', 'EmailController@destroy_draft');

	Route::post('dashboard/emails/send-email/{num}', 'EmailController@send_email');

	Route::post('dashboard/emails/trash/{num}', 'EmailController@trash_email');

	Route::post('dashboard/emails/destroy/{num}', 'EmailController@destroy_email');

	Route::get('dashboard/emails/move-to-sent/{id}', 'EmailController@move_to_sent');


	/*
	|----------------------------------------------------------------------------
	|HELP
	|----------------------------------------------------------------------------
	*/

	Route::get('dashboard/help', 'HelpController@index');
	Route::post('dashboard/help', 'HelpController@do_help');

	/*
	|----------------------------------------------------------------------------
	|SETTINGS
	|----------------------------------------------------------------------------
	*/

	Route::get('dashboard/settings', 'SettingController@index');

	Route::post('dashboard/settings', 'SettingController@settings');


});

/*
|----------------------------------------------------------------------------
|TRACKERS
|----------------------------------------------------------------------------
*/

	Route::get('tracker/{id}', 'SubscriberController@tracker');

	Route::get('unsubscribe/{id}', 'SubscriberController@unsubscribe');

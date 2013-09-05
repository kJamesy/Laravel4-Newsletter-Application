<?php

class FrontendController extends BaseController 
{

	public function index()
	{
		if (Sentry::check()) //More checks for the type of user
		{
			$user = Sentry::getUser();
			return Redirect::to('dashboard');
		}

		else
		{
			$users = User::take(2)->count();
			$sitenamerow = Setting::first(); 
			$sitename = '';
			if ($sitenamerow)
				$sitename = $sitenamerow->sitename;
			
			return View::make('frontend.index', array('users' => $users, 'sitename' => $sitename));
		}
	}

	public function setup()
	{
	    $rules = array(
	    		'first_name' => 'required|alpha_num|max:128', 
	    		'last_name' => 'required|alpha_num|max:128', 
	    		'email' => 'required|email|max:255|unique:users',
	    		'password' => 'required|min:7|confirmed',
	    		'sitename' => 'required|max:50'
	    	);

	    $validator = Validator::make(Input::all(), $rules);

	    if ($validator->fails())
	    {
	    	return Response::json($validator->messages());
	    }

	    else
	    {
	    	$setting = new Setting;
	    	$setting->sitename = Input::get('sitename');
	    	$setting->save();

		    $list = new Addressbook;
		    $list->name = 'General';
		    $list->save();	    	

			try
			{
			    $user = Sentry::register(array(
			        'email'    => Input::get('email'),
			        'password' => Input::get('password'),
			        'first_name' => Input::get('first_name'),
			        'last_name' => Input::get('last_name')
			    ), true);

			   	$feedback = array('success'=>'Great! Your system is ready to roll! You will be redirected to login form in 3 seconds.');
	    		return Response::json($feedback);
			}
			catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
			{
			    echo 'Login field is required.';
			}
			catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
			{
			    echo 'Password field is required.';
			}
			catch (Cartalyst\Sentry\Users\UserExistsException $e)
			{
			    echo 'User with this login already exists.';
			}	    	
	    }

	}

	public function login()
	{		
		try
		{
		    $credentials = array(
		        'email'    => Input::get('email'),
		        'password' => Input::get('password')
		    );

		    if (Input::get('remember') == 1)
		    	$user = Sentry::authenticateAndRemember($credentials);
		    else
		    	$user = Sentry::authenticate($credentials);

		    $url = URL::to('dashboard');

		    return Response::json(array('success' => 'You have arrived! Hang on and we\'ll redirect you shortly', 'url' => $url));
		}

		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
		    return Response::json(array('message' => 'Login field is required'));
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{		   
		    return Response::json(array('message' => 'Password field is required.'));
		}
		catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
		{
		    return Response::json(array('message' => 'Wrong password, please try again.'));
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    return Response::json(array('message' => 'User was not found.'));
		}
		catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{
		    return Response::json(array('message' => 'User is not activated.'));
		}

		catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
		{
		    return Response::json(array('message' => 'User is suspended.'));
		}
		catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
		{
		    return Response::json(array('message' => 'User is banned.'));
		}

	}			

	public function send_reminder()
	{

	    $rules = array('email' => 'required|email|exists:users');

	    $validator = Validator::make(Input::all(), $rules);

	    if ($validator->fails())
	    {
	    	return Response::json($validator->messages());
	    }

		try
		{
			$email = Input::get('email');

		    $user = Sentry::getUserProvider()->findByLogin($email);
		    $resetCode = $user->getResetPasswordCode();
		    $name = $user->first_name . ' ' . $user->last_name;

			$data = array('user' => $user);

			$count = 

			Mail::send('emails.reminder', $data, function($message) use ($user, $name)
			{
			    $message->to($user->email, $name)->subject('Reset your CARE Newsletter account password');
			});	

			if ($count > 0)
		    	return Response::json(array('success' => 'An email has been sent to you with instructions on how to reset your password.'));
			else
				return Response::json(array('error' => 'An error was encountered sending the email. Please try again or contact server admin.'));
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    return Response::json(array('error' => 'User with the given email was not found. Please try again'));
		}		
	}

	public function do_reset_pass()
	{
			
		$reset_code = Input::get('reset_code');

	    $rules = array('password' => 'required|min:6|confirmed', 'reset_code' => 'required');

	    $validator = Validator::make(Input::all(), $rules);

	    if ($validator->fails())
	    {
	    	return Response::json($validator->messages());
	    }

		try
		{
			$new_password = Input::get('password_confirmation');

		    $user = Sentry::getUserProvider()->findByResetPasswordCode($reset_code);

		    if($user->checkPassword($new_password))
		    {
		        return Redirect::to('password-reset/'.$reset_code)->with('check','Your password must be different from your existing password');
		    }

		    else
		    {
		        if ($user->attemptResetPassword($reset_code, $new_password))
		        {
		            return Response::json(array('success' =>'Password successfully reset. Click on \'Log in\' to log in'));
		        }

		        else
		        {
		           	return Response::json(array('error' =>'Password reset failure. Kindly try again or contact admin.'));
		        }
		    }
		}

		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    return Response::json(array('error' => 'User with the given credentials was not found. Please try again.'));
		}
		    
	}

	public function logout()
	{
		Sentry::logout();
		return Redirect::to('/');	
	}

}
jQuery(document).ready(function($) 
{
	$('.setup-box').click(function(e)
	{
		e.preventDefault();
		var url = $(this).attr('href');
		showSetup(url);
	});	

	function showSetup(location)
	{
	    $.ajax({
	        url: location,
	        cache: true
	        }).done(function(html) 
	    {

	        $(".row.newsletter").html(html).hide().fadeIn('3000');

			$('.form-horizontal.setup').submit(function(e)
			{
				e.preventDefault();

            	$('.panel-setup').addClass('panel-info').removeClass('panel-success').removeClass('panel-danger');
            	$('.panel-setup .panel-title').html('Welcome! Setup Admin Account and Sitename');
            	$('.panel-setup .panel-message').html('<span class="glyphicon glyphicon-bell"></span> Please note that all fields are required; password must contain an uppercase letter and a special character and be at least 7 characters long.');	
            	$('.form-group-password').removeClass('has-error');	

				var action = $(this).attr('action');
				var first_name = $.trim($('#userFirstName').val());
				var last_name = $.trim($('#userLastName').val());
				var email = $.trim($('#userEmail').val());
				var password = $.trim($('#userPassword').val());
				var password_confirmation = $.trim($('#userPasswordConfirmation').val());
				var sitename = $.trim($('#sitename').val());

				if (!password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/) || !password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))
				{
	            	$('.panel-setup').removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
	            	$('.panel-setup .panel-title').html('Please correct the error below');
	            	$('.panel-setup .panel-message').html('<span class="glyphicon glyphicon-ban-circle"></span> Password must contain an uppercase letter and a special character.');	
	            	$('.form-group-password').addClass('has-error');				
				}

				else
				{
					processSetupForm(action, first_name, last_name, email, password, password_confirmation, sitename);
				}
				
			});	      
	    }); 
	}	

	$('.login-box').click(function(e)
	{
		e.preventDefault();
		var url = $(this).attr('href');
		showLogin(url);
	});	

	function showLogin(location)
	{
	    $.ajax({
	        url: location,
	        cache: true
	        }).done(function(html) 
	    {

	        $(".row.newsletter").html(html).hide().fadeIn('3000');

			$('.password-request').click(function(e)
			{
				e.preventDefault();
				var url = $(this).attr('href');
				showPassRequest(url);
			});	  

			$('.form-horizontal.login').submit(function(e)
			{
				e.preventDefault();
				var action = $(this).attr('action');
				var email = $.trim($('#inputEmail').val());
				var password = $.trim($('#inputPassword').val());
				var remember = 0;
				if ($('#remember').prop('checked')) remember = 1;

				processLoginForm(action, email, password, remember);
			});	      
	    }); 
	}

	function showPassRequest(location)
	{
	    $.ajax({
	        url: location,
	        cache: true
	        }).done(function(html) 
	    {
	        $(".row.newsletter").html(html).hide().fadeIn('3000');

			$('.login-box').click(function(e)
			{
				e.preventDefault();
				var url = $(this).attr('href');
				showLogin(url);
			});	

			$('.password-request-form').submit(function(e)
			{
				e.preventDefault();
				var action = $(this).attr('action');
				var email = $.trim($('#reminderEmail').val());

				processReminderForm(action, email);
			});				        	        

	    }); 
	}

	function processSetupForm(action, first_name, last_name, email, password, password_confirmation, sitename)
	{
        $.post(action, {first_name:first_name, last_name:last_name, email:email, password:password, password_confirmation:password_confirmation, sitename:sitename}, function(data)
        {
        	var messages = '';

        	if(data['first_name'] != undefined)
        		messages += data['first_name'] + '<br />';
        	if(data['last_name'] != undefined)
        		messages += data['last_name'] + '<br />';
        	if(data['password'] != undefined)
        		messages += data['password'] + '<br />';
        	if(data['sitename'] != undefined)
        		messages += data['sitename'];

        	if(messages.length > 0)
        	{
            	$('.panel-setup').removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
            	$('.panel-setup .panel-title').html('<span class="glyphicon glyphicon-ban-circle"></span> Please correct the errors below');
            	$('.panel-setup .panel-message').html(messages);        		
        	}

            if (data['success'] !=undefined)
            {
           		$('.panel-setup').removeClass('panel-info').removeClass('panel-danger').addClass('panel-success');
            	$('.panel-setup .panel-title').html('Success!');
            	$('.panel-setup .panel-message').html(data['success']);

            	setTimeout(function()
            	{
					var url = $('.login-box2').attr('href');
					showLogin(url);
            	}, 3000);
            }   

        }, 'json'); 		
	}


	function processLoginForm(action, email, password, remember)
	{
        $.post(action, {email: email, password:password, remember: remember}, function(data)
        {
            if (data['success'] !=undefined)
            {
           		$('.panel-login').removeClass('panel-info').removeClass('panel-danger').addClass('panel-success');
            	$('.panel-login .panel-title').text('Success!');
            	$('.panel-login .panel-message').text(data['success']);

            	setTimeout(function()
            	{
            		window.location.replace(data['url']);
            	}, 3000);
            }   

            if (data['message'] !=undefined)
            {
            	$('.panel-login').removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
            	$('.panel-login .panel-title').text('Please correct the error below');
            	$('.panel-login .panel-message').text(data['message']);
            } 

        }, 'json'); 		
	}


	function processReminderForm(action, email)
	{
        $.post(action, {email: email}, function(data)
        {

            if (data['success'] !=undefined)
            {
           		$('.panel-reminder').removeClass('panel-info').removeClass('panel-danger').addClass('panel-success');
            	$('.panel-reminder .panel-title').text('Success!');
            	$('.panel-reminder .panel-message').text(data['success']);
            }   

            if (data['email'] !=undefined)
            {
            	$('.panel-reminder').removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
            	$('.panel-reminder .panel-title').text('Please correct the error below');
            	$('.panel-reminder .panel-message').text(data['email']);
            }                       

            else if (data['error'] !=undefined)
            {
           		$('.panel-reminder').removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
            	$('.panel-reminder .panel-title').text('Please correct the error below');
            	$('.panel-reminder .panel-message').text(data['error']);
            }

        }, 'json'); 		
	}

	$('.password-reset-form').submit(function(e)
	{
		e.preventDefault();
		var action = $(this).attr('action');
		var password = $.trim($('#inputResetPass').val());
		var password_confirmation = $.trim($('#inputResetPass2').val());
		var reset_code = $('#reset_code').val();

		$.post(action, {password: password, password_confirmation: password_confirmation, reset_code: reset_code}, function(data)
		{
            if (data['success'] !=undefined)
            {
           		$('.panel-reset').removeClass('panel-info').removeClass('panel-danger').addClass('panel-success');
            	$('.panel-reset .panel-title').text('Success!');
            	$('.panel-reset .panel-message').text(data['success']);
            }   

            if (data['password'] !=undefined)
            {
            	$('.panel-reset').removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
            	$('.panel-reset .panel-title').text('Please correct the error below');
            	$('.panel-reset .panel-message').text(data['password']);
            }                       

            else if (data['error'] !=undefined)
            {
           		$('.panel-reset').removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
            	$('.panel-reset .panel-title').text('Please correct the error below');
            	$('.panel-reset .panel-message').text(data['error']);
            }
		});
	});
});
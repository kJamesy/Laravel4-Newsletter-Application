jQuery(document).ready(function($) 
{
	$('.form-horizontal.setup').submit(function(e)
	{
		e.preventDefault();

    	$('.panel-setup').addClass('panel-info').removeClass('panel-success').removeClass('panel-danger');
    	$('.panel-setup .panel-title').html('Change your application settings');
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
            	$('.panel-setup .panel-message').html('<span class="glyphicon glyphicon-thumbs-up"></span> ' + data['success']);

            }   

        }, 'json'); 		
	}

});
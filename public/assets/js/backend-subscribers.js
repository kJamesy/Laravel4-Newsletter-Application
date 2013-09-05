jQuery(document).ready(function($) 
{
	$('#save-sub').click(function(e)
	{
		e.preventDefault();
		var url = $('.new-sub-form').attr('action');
		var first_name = $.trim($('.new-sub-form #sub-first-name').val());
		var last_name = $.trim($('.new-sub-form #sub-last-name').val());
		var email = $.trim($('.new-sub-form #sub-email').val());
		var active = 1;
		var redirect = 'yes';
		processSubForm('#new-sub', url, first_name, last_name, email, active, redirect);
	});

	function processSubForm(parent, action, firstname, lastname, email, active, redirect)
	{
        $.post(action, {first_name: firstname, last_name: lastname, email: email, active: active}, function(data)
        {
            if (data['success'] !=undefined)
            {
           		$('.panel-new-sub').removeClass('panel-info').removeClass('panel-danger').addClass('panel-success');
            	$('.panel-new-sub .panel-title').text('Success!');
            	$('.panel-new-sub .panel-message').text(data['success']);

            	if (redirect == 'yes')
            	{
	            	setTimeout(function()
	            	{
	            		location.reload(true);
	            	}, 1500);
            	}
            }   

            var errors = '';

            if (data['first_name'] !=undefined)
            {
            	errors += data['first_name'];
            	errors += '<br />';
            } 

            if (data['last_name'] !=undefined)
            {
            	errors += data['last_name'];
            	errors += '<br />';
            }

            if (data['email'] !=undefined)
            {
            	errors += data['email'];
            	errors += '<br />';
            }

            if (errors.length > 0)
            	processErrors(parent+' .panel-new-sub', errors);

            customFunction();

        }, 'json'); 		
	}

	function processErrors(panel, message)
	{
    	$(panel).removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
    	$(panel+' .panel-title').text('Please correct the error below');
    	$(panel+' .panel-message').html(message);		
	}

	$("#select-sub-update").change(function() 
	{
		var id = $(this).val();
		var url = $(this).attr('rel')+'/'+id;
		var $fname = $('.update-sub-form input#update-first-name');
		var $lname = $('.update-sub-form input#update-last-name');
		var $email = $('.update-sub-form input#update-email');
		var $yes = $('.update-sub-form #yes');
		var $no = $('.update-sub-form #no');
		var $savebtn = $('#save-update-sub');

		$yes.removeAttr('disabled');
		$no.removeAttr('disabled');
		$savebtn.removeAttr('disabled');

        $.post(url, function(data)
        {
        	$fname.removeAttr('disabled').val(data['first_name']);
        	$lname.removeAttr('disabled').val(data['last_name']);
        	$email.removeAttr('disabled').val(data['email']);
        	if (data['active'] == 1)
        		$yes.prop('checked', true);
        	else
        		$no.prop('checked', true);

        	$savebtn.attr('rel', data['id']);

			$savebtn.click(function(e)
			{
				e.preventDefault(); 
				$savebtn.button('loading');

				var url = $('.update-sub-form').attr('action')+'/'+$(this).attr('rel');
				var first_name = $.trim($fname.val());
				var last_name = $.trim($lname.val());
				var email = $.trim($email.val());
				var active = $(".update-sub-form :radio:checked").val();
				var redirect = 'no';
				processSubForm('#update-sub', url, first_name, last_name, email, active, redirect);
			});

        }, 'json'); 
	});

	$('#update-sub').on('hide.bs.modal', function () 
	{
		$('form.update-sub-form')[0].reset();
		$('form.update-sub-form input').each(function()
		{
			$(this).attr('disabled', 'disabled');
		});

		$('#save-update-sub').attr('disabled', 'disabled');
	});

	function customFunction()
	{
		if($('#save-update-sub').text() == 'Working...')
		{
			$('#save-update-sub').button('reset');
		}
	}

	$("#select-sub-delete").change(function()
	{
		$('#delete-sub-btn').removeAttr('disabled');
	});

	$('#delete-sub-btn').click(function(e)
	{
		e.preventDefault(); 
		$(this).attr('disabled', 'disabled');

		var id = $("#select-sub-delete").val();
		var action = $('.delete-sub-form').attr('action')+'/'+id;

        $.post(action, function(data)
        {
            if (data['success'] !=undefined)
            {
           		$('.panel-delete-sub').removeClass('panel-info').removeClass('panel-danger').addClass('panel-success');
            	$('.panel-delete-sub .panel-title').text('Success!');
            	$('.panel-delete-sub .panel-message').text(data['success']);

            	setTimeout(function()
            	{
            		location.reload(true);
            	}, 1500);
            }

            if (data['error'] != undefined)
            {
		    	$('.panel-delete-sub').removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
		    	$('.panel-delete-sub .panel-title').text('Please correct the error below');
		    	$('.panel-delete-sub .panel-message').text(data['error']);            	
            }   

        }, 'json'); 		

	});	

	$('#delete-sub').on('hide.bs.modal', function () 
	{
		$('form.delete-sub-form')[0].reset();

		$('#delete-sub-btn').attr('disabled', 'disabled');
	});	

	$('#export-subs').click(function(e)
	{
		e.preventDefault();
		var action = $(this).attr('href');

        $.post(action, function(data)
        {
            if (data['file'] !=undefined)
            {
            	setTimeout(function()
            	{
            		window.open(data['file']);
            	}, 1500);
            }

            if (data['error'] != undefined)
            {
		    	$('.panel-delete-sub').removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
		    	$('.panel-delete-sub .panel-title').text('Please correct the error below');
		    	$('.panel-delete-sub .panel-message').text(data['error']);            	
            }   

        }, 'json');
	});


    $('#browse-csv-trigger').click(function(e)
    {
        e.preventDefault();
        $('#csvfile').trigger('click');
    });

    var url = $('form.import-subs-form').attr('action');
    var defaulttext = $('.panel-message').text();
    var progress  = '<div class="progress progress-striped active"><div class="progress-bar" style="width: 100%"></div></div>';
    var $panel = $('.panel-import-subs');
    var $panelttl = $('.panel-import-subs .panel-title');
    var $panelmsg = $('.panel-import-subs .panel-message');
    

    $('#csvfile').fileupload({
        url: url,
        dataType: 'json'
    }).on('fileuploadadd', function (e, data) 
        {
            $('.panel-import-subs .panel-message').html(progress);
            $('.ui-button').button("disable");

        }).on('fileuploaddone', function (e, data) 
        {
            $.each(data.result.files, function (index, file) 
            {
                if (file.success != undefined)
                {
	           		$panel.removeClass('panel-info').removeClass('panel-danger').addClass('panel-success');
	            	$panelttl.text('Success!');
	            	$panelmsg.html(file.success);
                }
                
                else if (file.error != undefined)
                {
			    	$panel.removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
			    	$panelttl.text('Please correct the error below');
			    	$panelmsg.html(file.error);                       
                }

                else
                {
                    $panelmsg.html('An unknown error occurred. Kindly try again.').css('color', 'red');
                    $panelttl.text('Please correct the error below');
                }
            });

        }); 

		$('#import-subs').on('hide.bs.modal', function () 
		{
			$('form.import-subs-form')[0].reset();
			location.reload(true);
		}); 

		if ($('#select-subs-email').val() != null)
			$('.num-selected').text($('#select-subs-email').val().length);
		
		$('#select-subs-email').change(function()
		{
			var $this = $(this);
			var $numsel = $('.num-selected');
			var $nextbtn = $('#email-subs-btn');

			if ($this.val() != null)
				$numsel.text($this.val().length);
			else
				$numsel.text('0');

			if ($numsel.text() > 0)
				$nextbtn.removeAttr('disabled');
			else
				$nextbtn.attr('disabled', 'disabled');
		});

		$('#email-subs-btn').click(function(e)
		{
			e.preventDefault(); 

			var selected = $('#select-subs-email').val();

			var location = $(this).attr('rel');

		    $.ajax({
		        url: location,
		        cache: true
		        }).done(function(html) 
		    {

		    	$('#email-subs').modal('hide');

		    	$('.container-narrow h1').text('Email Selected Subscribers');

		    	var action = $('form.email-subs-form').attr('action');

		        $(".row.newsletter").html(html).hide().fadeIn(1000);

		        $.post(action, {subsarray: selected}, function(data)
		        {
			        var recipients = '';

			        $.each(data, function(index, subscriber)
			        {
			        	recipients += subscriber['first_name'] + ' ' + subscriber['last_name'] + '&lt;' + subscriber['email'] + '&gt;;';
			        });			        

			        $('#to-subs').html(recipients);  

		        }, 'json'); 


				$('#process-email').click(function(e)
				{
					e.preventDefault();
					var progress  = '<div class="progress progress-striped active"><div class="progress-bar" style="width: 100%"></div></div>';
					var reload_btn = '<button type="button" class="btn btn-primary btn-lg" id="return-to-subs" >&laquo; Return to Subscribers</button>';
					$('.panel-email-sub').removeClass('panel-danger').removeClass('panel-success').addClass('panel-info');
					$('.panel-email-sub .panel-title').text('Working...');
					$('.panel-email-sub .panel-message').html(progress);	

					var action = $('form.compose-email-form').attr('action');
					var from_name = $.trim($('form.compose-email-form #from-name').val());
					var from_email = $.trim($('form.compose-email-form #from-email').val());
					var subject = $.trim($('form.compose-email-form #subject').val());	
					var emailbody = $.trim(CKEDITOR.instances['ckeditor-1'].getData());				

					$.post(action, {from_name: from_name, from_email: from_email, to: selected, subject: subject, emailbody: emailbody}, function(data)
					{
			            if (data['validation'] != undefined)
			            {
					    	$('.panel-email-sub').removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
					    	$('.panel-email-sub .panel-title').text('Please correct the errors below');

					    	var messages = '';

					    	$.each(data['validation'], function(index, message)
					    	{
					    		messages += message + "<br />";
					    	});

					    	$('.panel-email-sub .panel-message').html(messages);            	
			            } 

			            else
			            {
			            	if (data['success'] != undefined)
			            	{
				           		$('.panel-email-sub').removeClass('panel-info').removeClass('panel-danger').addClass('panel-success');
				            	$('.panel-email-sub .panel-title').text('Success!');
				            	$('.panel-email-sub .panel-message').html(data['success'] + '<br /><br />' + reload_btn);	

				            	$('#return-to-subs').click(function(e)
				            	{
				            		e.preventDefault();
				            		window.location.reload(true);
				            	});	            		
			            	}
			            }

					});

				});     
		    }); 

		});


		$('#email-subs').on('hide.bs.modal', function () 
		{
			$('form.email-subs-form')[0].reset();
			$('.num-selected').text('0');
			$('#email-subs-btn').attr('disabled', 'disabled');
		}); 



});
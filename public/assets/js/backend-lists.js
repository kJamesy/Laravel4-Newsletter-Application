jQuery(document).ready(function($) 
{
	$('#save-list').click(function(e)
	{
		e.preventDefault();
		var url = $('.new-list-form').attr('action');
		var name = $.trim($('.new-list-form #list-name').val());
		var active = $(".new-list-form :radio:checked").val();
		processListForm('#new-list .panel-new-list', url, name, active);
	});

	function processListForm(parent, action, name, active)
	{
        $.post(action, {name: name, active: active}, function(data)
        {
            if (data['success'] !=undefined)
            {
           		$(parent).removeClass('panel-info').removeClass('panel-danger').addClass('panel-success');
            	$(parent+' .panel-title').text('Success!');
            	$(parent+' .panel-message').text(data['success']);
            }   

            var errors = '';

            if (data['name'] !=undefined)
            {
            	errors += data['name'];
            	errors += '<br />';
            } 

            if (data['active'] !=undefined)
            {
            	errors += data['active'];
            	errors += '<br />';
            }

            if (errors.length > 0)
            	processErrors(parent, errors);

        }, 'json'); 		
	}

	function processErrors(panel, message)
	{
    	$(panel).removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
    	$(panel+' .panel-title').text('Please correct the errors below');
    	$(panel+' .panel-message').html(message);		
	}

	$("#select-list-update").change(function() 
	{
		var id = $(this).val();
		var url = $(this).attr('rel')+'/'+id;
		var $name = $('.update-list-form input#update-list-name');
		var $yes = $('.update-list-form #update-yes');
		var $no = $('.update-list-form #update-no');
		var $savebtn = $('#save-update-list');

		$yes.removeAttr('disabled');
		$no.removeAttr('disabled');
		$savebtn.removeAttr('disabled');

        $.post(url, function(data)
        {
        	$name.removeAttr('disabled').val(data['name']);
        	if (data['active'] == 1)
        		$yes.prop('checked', true);
        	else
        		$no.prop('checked', true);

        	$savebtn.attr('rel', data['id']);

			$savebtn.click(function(e)
			{
				e.preventDefault(); 
				$savebtn.attr('disabled', 'disabled');

				var url = $('.update-list-form').attr('action')+'/'+$(this).attr('rel');
				var name = $.trim($name.val());
				var active = $(".update-list-form :radio:checked").val();
				processListForm('#update-list .panel-update-list', url, name, active);
			});

        }, 'json'); 
	});

	function reloadPage()
	{
    	location.reload(true);	
	}

	$('#new-list').on('hide.bs.modal', function() 
	{
		$('form.new-list-form')[0].reset();
		reloadPage();
	});

	$('#update-list').on('hide.bs.modal', function() 
	{
		$('form.update-list-form')[0].reset();
		$.each($('form.update-list-form input'), function()
		{
			$(this).attr('disabled', 'disabled');
		});	

		$('#save-update-list').attr('disabled', 'disabled');
		reloadPage();
	});					


	$("#select-list-delete").change(function()
	{
		$('#delete-list-btn').removeAttr('disabled');
	});

	$('#delete-list-btn').click(function(e)
	{
		e.preventDefault(); 
		$(this).attr('disabled', 'disabled');

		var id = $('#select-list-delete').val();
		var action = $('.delete-list-form').attr('action')+'/'+id;

        $.post(action, function(data)
        {
            if (data['success'] !=undefined)
            {
           		$('.panel-delete-list').removeClass('panel-info').removeClass('panel-danger').addClass('panel-success');
            	$('.panel-delete-list .panel-title').text('Success!');
            	$('.panel-delete-list .panel-message').text(data['success']);

            	setTimeout(function()
            	{
            		location.reload(true);
            	}, 1500);
            }

            if (data['error'] != undefined)
            {
		    	$('.panel-delete-list').removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
		    	$('.panel-delete-list .panel-title').text('Please correct the error below');
		    	$('.panel-delete-list .panel-message').text(data['error']);            	
            }   

        }, 'json'); 		

	});	

	$('#delete-list').on('hide.bs.modal', function () 
	{
		$('form.delete-list-form')[0].reset();

		$('#delete-list-btn').attr('disabled', 'disabled');
		reloadPage();
	});	

	var $select = $('#select-add-to-list');
	var $savebtn = $('#add-subs-to-list-btn');
	$savebtn.attr('rel', '');
	var $numsel = $('.num-selected');	

	$("#select-list-add").change(function() 
	{
		var id = $(this).val();
		var url = $(this).attr('rel')+'/'+id;
		var $select = $('#select-add-to-list');
		var $savebtn = $('#add-subs-to-list-btn');
		$savebtn.attr('rel', '');
		var $numsel = $('.num-selected');

        $.post(url, function(data)
        {
        	$numsel.text('0');
        	$savebtn.attr('rel', id);
        	$savebtn.attr('disabled', 'disabled');

        	if (data.length > 0)
        	{
	        	$select.removeAttr('disabled');
	        	$('#temporay-holder').remove();
	        	var results = '';

	        	$.each(data, function(index, value)
	        	{
	        		results += "<option value='" + value['id'] + "'>" + value['first_name'] + ' ' + value['last_name'] + "</option>";
	        	});

	        	$select.html(results);    	
	        }

	        else
	        {
	        	$('temporay-holder').text('Selected List already has all subscribers');
	        }

        }, 'json'); 
	});


	if ($select.val() != null)
		$numsel.text($select.val().length);
	
	$select.change(function()
	{
		var $this = $(this);

		if ($this.val() != null)
			$numsel.text($this.val().length);
		else
			$numsel.text('0');

		if ($numsel.text() > 0)
			$savebtn.removeAttr('disabled');
		else
			$savebtn.attr('disabled', 'disabled');
	});	        	

	$savebtn.click(function(e)
	{
		e.preventDefault(); 
		$savebtn.attr('disabled', 'disabled'); 

		var action = $('form.add-to-list-form').attr('action')+'/'+$savebtn.attr('rel');
		var selected = $select.val();

        $.post(action, {subsarray: selected}, function(data)
        {
	        if(data['success'] != undefined)
	        {
           		$('.panel-add-to-list').removeClass('panel-info').removeClass('panel-danger').addClass('panel-success');
            	$('.panel-add-to-list .panel-title').text('Success!');
            	$('.panel-add-to-list .panel-message').text(data['success']);				        	
	        } 

        	setTimeout(function()
        	{
        		location.reload(true);
        	}, 1500);				        

        }, 'json'); 
	});		

	$('#add-to-list').on('hide.bs.modal', function () 
	{
		$('form.add-to-list-form')[0].reset();
		reloadPage();
	});	


	$("#select-list-remove").change(function() 
	{
		var id = $(this).val();
		var url = $(this).attr('rel')+'/'+id;
		var $select = $('#select-remove-from-list');
		var $savebtn = $('#remove-subs-from-list-btn');
		$savebtn.attr('rel', '');
		var $numsel = $('.remove.num-selected');

        $.post(url, function(data)
        {
        	$numsel.text('0');
        	$savebtn.attr('rel', id);
        	$savebtn.attr('disabled', 'disabled');

        	if (data.length > 0)
        	{
	        	$select.removeAttr('disabled');
	        	$('#temporay-holder2').remove();
	        	var results = '';

	        	$.each(data, function(index, value)
	        	{
	        		results += "<option value='" + value['id'] + "'>" + value['first_name'] + ' ' + value['last_name'] + "</option>";
	        	});

	        	$select.html(results);    	
	        }

	        else
	        {
	        	$('temporay-holder2').text('Selected List does not have any subscribers');
	        }

        }, 'json'); 
	});

	var $removeselect = $('#select-remove-from-list');
	var $removesavebtn = $('#remove-subs-from-list-btn');
	var $removenumsel = $('.remove.num-selected');

	if ($removeselect.val() != null)
		$removenumsel.text($removeselect.val().length);
	
	$removeselect.change(function()
	{
		var $this = $(this);

		if ($this.val() != null)
			$removenumsel.text($this.val().length);
		else
			$removenumsel.text('0');

		if ($removenumsel.text() > 0)
			$removesavebtn.removeAttr('disabled');
		else
			$removesavebtn.attr('disabled', 'disabled');
	});	        	

	$removesavebtn.click(function(e)
	{
		e.preventDefault(); 
		$removesavebtn.attr('disabled', 'disabled'); 

		var action = $('form.remove-from-list-form').attr('action')+'/'+$removesavebtn.attr('rel');
		var selected = $removeselect.val();

        $.post(action, {subsarray: selected}, function(data)
        {
	        if(data['success'] != undefined)
	        {
           		$('.panel-remove-from-list').removeClass('panel-info').removeClass('panel-danger').addClass('panel-success');
            	$('.panel-remove-from-list .panel-title').text('Success!');
            	$('.panel-remove-from-list .panel-message').text(data['success']);				        	
	        } 

        	setTimeout(function()
        	{
        		location.reload(true);
        	}, 1500);				        

        }, 'json'); 
	});		


	$('#remove-from-list').on('hide.bs.modal', function () 
	{
		$('form.remove-from-list-form')[0].reset();
		reloadPage();
	});	


	if ($('#select-lists-email').val() != null)
		$('.num-selected').text($('#select-lists-email').val().length);
	
	$('#select-lists-email').change(function()
	{
		var $this = $(this);
		var $numsel = $('.num-selected');
		var $nextbtn = $('#email-lists-btn');

		if ($this.val() != null)
			$numsel.text($this.val().length);
		else
			$numsel.text('0');

		if ($numsel.text() > 0)
			$nextbtn.removeAttr('disabled');
		else
			$nextbtn.attr('disabled', 'disabled');
	});	

	$('#email-lists-btn').click(function(e)
	{
		e.preventDefault(); 

		var selected = $('#select-lists-email').val();

		var location = $(this).attr('rel');

	    $.ajax({
	        url: location,
	        cache: true
	        }).done(function(html) 
	    {

	    	$('#email-list').modal('hide');

	    	$('.container-narrow h1').text('Email Subscribers in Selected List(s)');

	    	var action = $('form.email-list-form').attr('action');

	        $(".row.newsletter").hide().html(html).fadeIn(1000);

	        $.post(action, {listsarray: selected}, function(data)
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
				var reload_btn = '<button type="button" class="btn btn-primary btn-lg" id="return-to-subs" >&laquo; Return to Lists</button>';
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

				}, 'json');

			});     
	    }); 

	});


	$('#email-list').on('hide.bs.modal', function () 
	{
		$('form.email-list-form')[0].reset();
	});	

});
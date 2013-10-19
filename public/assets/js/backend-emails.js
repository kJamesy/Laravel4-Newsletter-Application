jQuery(document).ready(function($) 
{
	var starturl = $('#emailsTab a.compose').attr('rel');
	var starttarget = 'compose';
	showCompose(starturl,starttarget);

	$('#emailsTab a').click(function (e) 
	{
	  	e.preventDefault();
	  	$this = $(this);

	  	var location = $this.attr('rel');
	  	var target = $this.attr('class');

	  	if($this.hasClass('compose'))
	  		showCompose(location,target);

	  	$this.tab('show');
	});

	//Navigate to the relevant tab
	var thisurl = window.location.href;
	var querystring = '';
	var navtab = '';

	if (thisurl.indexOf('?') > 0)
		querystring = thisurl.split('?')[1];

	if (querystring.indexOf('=') > 0)
		navtab = querystring.split('=')[1];

	var tablist = ['drafts', 'sent', 'trash'];

	$.each(tablist, function(index, value)
	{
		if (value == navtab)
		{
			tabpos = index+1;
			selector = "#emailsTab li:eq(" + tabpos + ") a";
			$(selector).tab('show');
		}
	});

	function showCompose(url,selector)
	{
	    $.ajax({
	        url: url,
	        cache: false
	        }).done(function(html) 
			    {

			    	$('#'+selector).html(html);
			    	$ourselect = $('#el');

			    	$ourselect.select2({
			    		width: 'resolve'
			    	});


			    	$('#subject').val($('.content-for-subject').html());
			    	CKEDITOR.instances['ckeditor-2'].setData($('.content-for-editor').html());

					var counter = $('.recipient-count');						

			    	$ourselect.change(function()
			    	{
			    		if($(this).val() != null)
			    			counter.text($(this).val().length);
			    		else
			    			counter.text(0);
			    	});


			    	$('#move-draft').click(function(event) 
			    	{
			    		event.preventDefault();
						$this = $(this);
						$this.addClass('disabled');

						var progress  = '<div class="progress progress-striped active"><div class="progress-bar" style="width: 100%"></div></div>';
						$('.panel-email-sub').removeClass('panel-danger').removeClass('panel-success').addClass('panel-info');
						$('.panel-email-sub .panel-title').text('Moving...');
						$('.panel-email-sub .panel-message').html(progress);	

						var action = $this.attr('href');
						var subject = $.trim($('form.compose-email-form #subject').val());	
						var emailbody = $.trim(CKEDITOR.instances['ckeditor-2'].getData());		

						$.post(action, {subject: subject, emailbody: emailbody}, function(data)
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
					            	$('.panel-email-sub .panel-message').html(data['success']);	

									var url = window.location.href.split('?')[0]+'?tab=drafts';
									refreshPage(url);		            	       		
				            	}
				            }

				            $this.removeClass('disabled');

						}, 'json');

			    	});


				    var state3;

				    $('#checkall-drafts2').click(function() 
				    {
				        $this = $(this);

				        state3 = !state3;
				        if (state3) 
				        {
				           $(':checkbox.destroy-drafts-checkbox').each(function()
				            {
				                $(this).prop('checked', true);
				            });
				        }

				        else
				        {
				           $(':checkbox.destroy-drafts-checkbox').each(function()
				            {
				                $(this).prop('checked', false);
				            }); 
				        }

				    });  

				    $(':checkbox.destroy-drafts-checkbox').change(function()
				    {
						if ($('input[name="checkbox-drafts[]"]:checked').length > 0)
							$(".drafts-destroy-btn").removeClass('disabled');
					    else
					    	$(".drafts-destroy-btn").addClass('disabled');
					});

					$('.drafts-destroy-btn').click(function()
					{
						$this = $(this);
						var url = $this.attr('rel');
						$this.addClass('disabled');

						var selected = [];

						if ($('input[name="checkbox-drafts[]"]:checked').length > 0)
						{

							$('input[name="checkbox-drafts[]"]:checked').each(function()
							{
								selected.push($(this).val());
							});


							$.post(url, {selected:selected}, function(data)
							{
								if(data['success'] != undefined)
								{
									$('.say-something-drafts').append('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data['success'] + '</div>');
									var url = window.location.href.split('?')[0]+'?tab=drafts';
									refreshPage(url);	
								}

								else
								{
									alert('An error occurred. Kindly try again');
								}

					            $this.removeClass('disabled');

							}, 'json');		
						}

						else
						{
							alert('Please select something');
							$this.removeClass('disabled');
						}

					});



					$('#process-email').click(function(e)
					{
						e.preventDefault();
						$this = $(this);
						$this.addClass('disabled');

						var progress  = '<div class="progress progress-striped active"><div class="progress-bar" style="width: 100%"></div></div>';
						$('.panel-email-sub').removeClass('panel-danger').removeClass('panel-success').addClass('panel-info');
						$('.panel-email-sub .panel-title').text('Sending...');
						$('.panel-email-sub .panel-message').html(progress);	

						var action = $('form.compose-email-form').attr('action');
						var from_name = $.trim($('form.compose-email-form #from-name').val());
						var from_email = $.trim($('form.compose-email-form #from-email').val());
						var selected = $ourselect.val();
						var subject = $.trim($('form.compose-email-form #subject').val());	
						var emailbody = $.trim(CKEDITOR.instances['ckeditor-2'].getData());		

						if(selected == null)
						{
							$('.panel-email-sub').removeClass('panel-info').removeClass('panel-success').addClass('panel-danger');
							$('.panel-email-sub .panel-title').text('Please correct the errors below');							
							$('.panel-email-sub .panel-message').html('Oops! You forgot to select recipients');  
							$this.removeClass('disabled');
						}		

						else
						{

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
						            	$('.panel-email-sub .panel-message').html(data['success']);	

										var url = window.location.href.split('?')[0]+'?tab=sent';
										refreshPage(url);							            	      		
					            	}
					            }

					            $this.removeClass('disabled');

							}, 'json');
						}

					}); 

		     
			    }); 
	}

    var state;

    $('#checkall1').click(function() 
    {
        $this = $(this);

        state = !state;
        if (state) 
        {
           $(':checkbox.delete-checkbox').each(function()
            {
                $(this).prop('checked', true);
            });
        }

        else
        {
           $(':checkbox.delete-checkbox').each(function()
            {
                $(this).prop('checked', false);
            }); 
        }

    });  

    $(':checkbox.delete-checkbox').change(function()
    {
		if ($('input[name="checkbox1[]"]:checked').length > 0)
			$(".delete-btn").removeClass('disabled');
	    else
	    	$(".delete-btn").addClass('disabled');
	});

	$('.delete-btn').click(function()
	{
		$this = $(this);
		var url = $this.attr('rel');
		$this.addClass('disabled');

		var selected = [];

		if ($('input[name="checkbox1[]"]:checked').length > 0)
		{

			$('input[name="checkbox1[]"]:checked').each(function()
			{
				selected.push($(this).val());
			});


			$.post(url, {selected:selected}, function(data)
			{
				if(data['success'] != undefined)
				{
					$('.say-something').append('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data['success'] + '</div>');
					var url = window.location.href.split('?')[0]+'?tab=trash';
					refreshPage(url);						
				}

				else
				{
					alert('An error occurred. Kindly try again');
				}

	            $this.removeClass('disabled');

			}, 'json');		
		}

		else
		{
			alert('Please select something');
			$this.removeClass('disabled');
		}

	});

    var state2;

    $('#checkall2').click(function() 
    {
        $this = $(this);

        state2 = !state2;
        if (state2) 
        {
           $(':checkbox.destroy-checkbox').each(function()
            {
                $(this).prop('checked', true);
            });
        }

        else
        {
           $(':checkbox.destroy-checkbox').each(function()
            {
                $(this).prop('checked', false);
            }); 
        }

    });  

    $(':checkbox.destroy-checkbox').change(function()
    {
		if ($('input[name="checkbox2[]"]:checked').length > 0)
			$(".destroy-btn").removeClass('disabled');
	    else
	    	$(".destroy-btn").addClass('disabled');
	});

	$('.destroy-btn').click(function()
	{
		$this = $(this);
		var url = $this.attr('rel');
		$this.addClass('disabled');

		var selected = [];

		if ($('input[name="checkbox2[]"]:checked').length > 0)
		{

			$('input[name="checkbox2[]"]:checked').each(function()
			{
				selected.push($(this).val());
			});


			$.post(url, {selected:selected}, function(data)
			{
				if(data['success'] != undefined)
				{
					$('.say-something2').append('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data['success'] + '</div>');
					var url = window.location.href.split('?')[0]+'?tab=trash';
					refreshPage(url);						
				}

				else
				{
					alert('An error occurred. Kindly try again');
				}

	            $this.removeClass('disabled');

			}, 'json');		
		}

		else
		{
			alert('Please select something');
			$this.removeClass('disabled');
		}

	});


	function refreshPage(url)
	{
		setTimeout(function()
		{
			window.location.replace(url);
		}, 1000);		
	}

});

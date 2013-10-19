@extends('dashboard._template')
@section('title')
	{{$sitename}} | Dashboard
@stop
@section('extracss')
	{{HTML::style('assets/easy-pie-chart/jquery.easy-pie-chart.css')}}
@stop
@section('extrajs')
	{{HTML::script('assets/easy-pie-chart/jquery.easy-pie-chart.js')}}
	{{HTML::script('assets/js/backend-index.js')}}
@stop
@section('page')
	<div class="header">
		<ul class="nav nav-pills pull-right">
			<li class="active"><a href="{{URL::to('dashboard')}}">Dashboard</a></li>
			<li><a href="{{URL::to('dashboard/subscribers')}}">Subscribers</a></li>
			<li><a href="{{URL::to('dashboard/lists')}}">Lists</a></li>
			<li><a href="{{URL::to('dashboard/emails')}}">Emails</a></li>
			<li><a href="{{URL::to('dashboard/help')}}">Help</a></li>
			<li><a href="{{URL::to('dashboard/settings')}}"><span class="glyphicon glyphicon-wrench"></span></a></li>
			<li><a href="{{URL::to('logout')}}"><span class="glyphicon glyphicon-off" style="color: Firebrick; font-weight: 600"></span></a></li>
		</ul>
		<h1 class="no-margins">{{$sitename}}</h1>
	</div>
    <div class="jumbotron">
    	<center><h1>Welcome, {{$user->first_name}}! Your Stats:</h1></center>
	</div>
	<div class="row newsletter dashboard-stats">
		<div class="row" style="text-align:center; font-style:italic">10 minute cache applies</div>
		<div class="row">
			<div class="col-lg-6">
				@if ($impressions > 0)
					<div class="panel-pie">                          
						<div class="chart three-cols">
							<div class="percentage" data-percent="{{$read_emails}}">
								<span>{{$read_emails}}</span><sup>%</sup>
							</div>
							<div class="label label-success"><b>Read</b></div>
						</div>
					  
						<div class="chart three-cols">
							<div class="percentage" data-percent="{{$unread_emails}}">
								<span>{{$unread_emails}}</span><sup>%</sup>
							</div>
							<div class="label label-warning"><b>Unread</b></div>
						</div>

						<div class="chart three-cols">
							<div class="percentage" data-percent="{{$bounced_emails}}">
								<span>{{$bounced_emails}}</span><sup>%</sup>
							</div>
							<div class="label label-danger"><b>Bounced</b></div>
						</div>
					</div>
					<h4>Out of the {{$emails_num}} emails sent with {{$impressions}} impressions</h4>
				@else
					<h2>Read/Unread/Unsent Emails</h2>
					&#10004; When you <a href="{{URL::to('dashboard/emails')}}">send</a> emails, each of them will be tracked using an invisible image<br />
					&#10004; When the user <abbr title="With images enabled">reads*</abbr> the email, we wil be able to record that the email has been read<br />
					&#10004; These stats will be available here.
					<br /><br /><a class="btn btn-default" href="{{URL::to('dashboard/emails')}}">Send an email &rarr;</a>
				@endif                    
			</div> 
			<div class="col-lg-6">
				@if ($impressions > 0)
					<div class="panel-pie">                          
						<div class="chart one-col">
							<div class="percentage" data-percent="{{$unsubscribed_emails}}">
								<span>{{$unsubscribed_emails}}</span><sup>%</sup>
							</div>
							<div class="label label-danger"><b>Clicks on 'unsubscribe' link</b></div>
						</div>
					</div>
					<h4>Out of the {{$impressions}} emails received</h4>
				@else
					<h2>Unsubscribers</h2>
					&#10004; Each email you send will have a customised 'unsubscribe' link for each subscriber<br />
					&#10004; When a user clicks on this link, we will record a few details <br />
					&#10004; These details will be available here.
					<br /><br /><a class="btn btn-default" href="{{URL::to('dashboard/emails')}}">Send an email &rarr;</a>
				@endif
			</div>  
		</div>

		<div class="row">
			<div class="col-lg-12">
				@if ($browser_outof > 0)
					<div class="panel-pie">                          
						@foreach($browsers_emails as $num => $browser_email)
							<div class="chart five-cols">
								<div class="percentage" data-percent="{{$browsers_array[$num]}}">
									<span>{{$browsers_array[$num]}}</span><sup>%</sup>
								</div>
								<div class="label label-success">
									@if($browser_email->browser == '')
										<b>Unknown</b>
									@elseif($browser_email->browser == 'Internet Explorer 7.0')
										<b>Windows Outlook</b>	
									@elseif($browser_email->browser == 'Mozilla 5.0')
										<b>Apple Outlook</b>											
									@elseif($browser_email->browser != '')
										<b>{{$browser_email->browser}}</b>
									@endif
								</div>
							</div>
						@endforeach
					</div>
					<h4>Top 5 of the {{$browser_outof}} different browsers used to read your emails</h4>
				@else
					<h2>Recipients' Browsers</h2>
					&#10004; For fine-tuning of your emails, you may want to know the browsers mostly used by your subscribers <br />
					&#10004; The tracker mentioned above will also record information on these browsers and their versions<br />
					&#10004; Statistics on the 5 most popular browsers used to read your emails will be available here.
					<br /><br /><a class="btn btn-default" href="{{URL::to('dashboard/emails')}}">Send an email &rarr;</a>
				@endif
			</div> 
		</div>

		<div class="row">
 			<div class="col-lg-12">
				@if ($platform_outof > 0)
					<div class="panel-pie">                          
						@foreach($platforms_emails as $num => $platform_email)
							<div class="chart five-cols">
								<div class="percentage" data-percent="{{$platforms_array[$num]}}">
									<span>{{$platforms_array[$num]}}</span><sup>%</sup>
								</div>
								<div class="label label-success">
									@if($platform_email->platform != '')
										<b>{{$platform_email->platform}}</b>
									@else
										<b>Unknown</b>
									@endif
								</div>
							</div>
						@endforeach
					</div>
					<h4>Top 5 of the {{$platform_outof}} different platforms used to read your emails</h4>
				@else
					<h2>Recipients' Platforms</h2>
					&#10004; Similar to browsers, you may also want to know what platform your subscribers read emails on (e.g. windows, android, apple, iPhone) <br />
					&#10004; The tracker will also record this information<br />
					&#10004; The five most popular platforms will be displayed here, in the order of percentage popularity.
					<br /><br /><a class="btn btn-default" href="{{URL::to('dashboard/emails')}}">Send an email &rarr;</a>
				@endif
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6">
				@if ($subscribers->count() > 0)
					<div class="panel-pie">                          
						<div class="chart">
							<div class="percentage" data-percent="{{$active_subs}}">
								<span>{{$active_subs}}</span><sup>%</sup>
							</div>
							<div class="label label-success"><b>Active</b></div>
						</div>
					  
						<div class="chart">
							<div class="percentage" data-percent="{{$inactive_subs}}">
								<span>{{$inactive_subs}}</span><sup>%</sup>
							</div>
							<div class="label label-danger"><b>Inactive</b></div>
						</div>
					</div>
					<h4>Out of the {{$subscribers->count()}} subscribers registered</h4>
				@else
					<h2>Subscribers</h2>
					&#10004; A count of all subscribers registered in your email application <br />
					&#10004; Statistics on how many are active or inactive will go here. 
					<br /><br /> <a class="btn btn-default" href="{{URL::to('dashboard/subscribers')}}">Add subscribers &rarr;</a>
				@endif
			</div> 

			<div class="col-lg-6">
				@if ($inactive_outof > 0)
					<div class="panel-pie">                          
						<div class="chart">
							<div class="percentage" data-percent="{{$self_deactivated}}">
								<span>{{$self_deactivated}}</span><sup>%</sup>
							</div>
							<div class="label label-info"><b>Self-deactivated</b></div>
						</div>
					  
						<div class="chart">
							<div class="percentage" data-percent="{{$admin_deactivated}}">
								<span>{{$admin_deactivated}}</span><sup>%</sup>
							</div>
							<div class="label label-info"><b>Admin-deactivated</b></div>
						</div>
					</div>
					<h4>Out of the {{$inactive_outof}} inactive subscribers</h4>
				@else
					<h2>Inactive Subscribers</h2>
					&#10004; A count of all inactive subscribers in your email application <br />
					&#10004; Here will be information on whether they self-deactivated or were deactivated by admin
					<br /><br /> <a class="btn btn-default" href="{{URL::to('dashboard/subscribers')}}">Add subscribers &rarr;</a>
				@endif
			</div>
		</div>  
	</div>
@stop
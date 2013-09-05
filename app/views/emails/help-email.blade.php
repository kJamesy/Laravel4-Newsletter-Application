<!DOCTYPE html>
<html lang="en-GB">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
			{{$emailbody}}
		</div>		
		<div style="margin-top: 35px; border-top: 1px solid #EEEEEE;">
			<div>Website URL: <a href="{{$page}}">{{$page}}</a></div>
			<div>IP Address: {{$userIP}}</div>
			<div>Browser: {{$userbrowser}}</div>
			<div>Platform: {{$userplatform}}</div>
		</div>				
	</body>
</html>

<!DOCTYPE html>
<html lang="en-GB">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
			<p>Hi {{$subscriber->first_name }},</p>
		</div>
		<div>
			{{$emailbody}}
			<a href="{{$unsubscribe}}">Unsubscribe</a>
			<img src="{{$tracker}}" alt='+' />
		</div>						
	</body>
</html>

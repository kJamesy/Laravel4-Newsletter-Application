<!DOCTYPE html>
<html lang="en-GB">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Password Reset</h2>
		<div>
			<p>Hi {{ $user->first_name }},</p>
		</div>
		<div>
			<p>
				To reset your Jl4 newsletter account password, please follow the link below:
			</p><p>
				<a href='{{URL::to("password-reset/{$user->reset_password_code}") }}'>{{ URL::to("password-reset/{$user->reset_password_code}") }}</a>
			</p>
		</div>
		<div>
			<p>Regards</p>
		</div>						
	</body>
</html>

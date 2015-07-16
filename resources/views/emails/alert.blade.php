<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
			Hello, <br><br>
			This is an automated message to inform you that an alert you are subscribed to has been activated.<br><br>
			Alert: <strong>{{ $alert->name }}</strong>
			
			@if($alert->resilientTrigger)
				<br><br>
				<strong>IMPORTANT!</strong><br><br>
				This alert has also been specified to activate Resilient mode when met. Therefore, the system will now enter <strong>Resilient</strong> mode. You will be notified again once the system is restored to Sustainable mode.
			@endif
		</div>
	</body>
</html>

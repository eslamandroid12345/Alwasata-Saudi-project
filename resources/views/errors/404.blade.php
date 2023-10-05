<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title> {{ MyHelpers::guest_trans('Page Not Fount') }}</title>

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Nunito:400,700" rel="stylesheet">

	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="{{ url('error/css/style.css') }}" />



</head>

<body>
	
	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404"></div>
			<h1>404</h1>
			<h2>{{ MyHelpers::guest_trans('Page Not Fount') }}</h2>
			<p>{{ MyHelpers::guest_trans('Sorry but the page you are looking for does not exist, have been removed. name changed or is temporarily unavailable') }}</p>
			<a href="{{url()->previous() }}">{{ MyHelpers::guest_trans('Back to previous page') }}</a>
		</div>
	</div>

</body><!-- {{ url('interface_style/images/icon/bg_whoweare.png') }} -->

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
       <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
       <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Publish your profile for public</h3>
    </div>
    <div class="panel-body">
        <form action="{{ url('email/publishprofile/'.$userID) }}" method="post">
            {!! csrf_field() !!}
            <p class="text-center">Welcome.</p>
            <p class="text-center">{!! $emailid !!}</p>
            <p class="text-center">Thank you for signing up for CareerTag!</p>
            Click here to publish your profile: <a href="{{ url('email/publishprofile/'.$userID) }}">{{ url('email/publishprofile/'.$userID) }}</a>
        </form>
    </div>
</div>

</body>
</html>
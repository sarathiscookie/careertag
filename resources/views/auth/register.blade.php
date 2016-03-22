<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
	<meta charset="utf-8"/>
	<title>careertag BETA - Registrierung</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<link rel="apple-touch-icon" href="/pages-frontend/ico/60.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/pages-frontend/ico/76.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/pages-frontend/ico/120.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/pages-frontend/ico/152.png">
	<link rel="icon" type="image/x-icon" href="favicon.ico"/>
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-touch-fullscreen" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	<!-- BEGIN PLUGINS -->
	<link href="/assets-frontend/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
	<link href="/assets-frontend/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="/assets-frontend/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
	<link href="/assets-frontend/plugins/swiper/css/swiper.css" rel="stylesheet" type="text/css" media="screen"/>
	<!-- END PLUGINS -->
	<!-- BEGIN PAGES CSS -->
	<link class="main-stylesheet" href="/pages-frontend/css/pages.css" rel="stylesheet" type="text/css"/>
	<link class="main-stylesheet" href="/pages-frontend/css/pages-icons.css" rel="stylesheet" type="text/css"/>
	<!-- BEGIN PAGES CSS -->
</head>
<body class="pace-white">
<!-- BEGIN HEADER -->
<nav class="header bg-header transparent-dark " data-pages="header" data-pages-header="autoresize" data-pages-resize-class="dark">
	<div class="container relative">
		<!-- BEGIN LEFT CONTENT -->
		<div class="pull-left">
			<!-- .header-inner Allows to vertically Align elements to the Center-->
			<div class="header-inner" style="padding-left: 15px;">
				<!-- BEGIN LOGO -->
				<a href="/"><img src="/assets-frontend/images/logo-ct.png" class="logo" alt=""></a>
				<a href="/"><img src="/assets-frontend/images/logo-ct.png" class="alt" alt=""></a>
			</div>
		</div>
		<!-- BEGIN HEADER TOGGLE FOR MOBILE & TABLET -->
		<div class="pull-right">
			<div class="header-inner">
				<a href="#" class="search-toggle visible-sm-inline visible-xs-inline p-r-10" data-toggle="search"><i class="fs-14 pg-search"></i></a>
				<div class="visible-sm-inline visible-xs-inline menu-toggler pull-right p-l-10" data-pages="header-toggle" data-pages-element="#header">
					<div class="one"></div>
					<div class="two"></div>
					<div class="three"></div>
				</div>
			</div>
		</div>
		<!-- END HEADER TOGGLE FOR MOBILE & TABLET -->
		<!-- BEGIN RIGHT CONTENT -->
		<div class="menu-content mobile-dark pull-right clearfix" data-pages-direction="slideRight" id="header">
			<!-- BEGIN HEADER CLOSE TOGGLE FOR MOBILE -->
			<div class="pull-right">
				<a href="#" class="padding-10 visible-xs-inline visible-sm-inline pull-right m-t-10 m-b-10 m-r-10" data-pages="header-toggle" data-pages-element="#header">
					<i class=" pg-close_line"></i>
				</a>
			</div>
			<!-- END HEADER CLOSE TOGGLE FOR MOBILE -->
			<!-- BEGIN MENU ITEMS -->
			<div class="header-inner">
				<ul class="menu">
					<li>
						<a href="/" class="active"><i class="fs-14 pg-home"></i></a>
					</li>
					@if (Auth::check())
						<li>
							<a href="{{ Auth::user()->alias }}/edit">Mein Profil</a>
						</li>
						<li>
							<a href="/auth/logout" >Ausloggen</a>
						</li>
					@else
						<li>
							<a href="/auth/register">Registrierung</a>
						</li>
						<li>
							<a href="/auth/login" >Login</a>
						</li>
					@endif
					<li>
						<a href="/kontakt">Kontakt</a>
					</li>
				</ul>
				<div class="font-arial m-l-35 m-r-35 m-b-20 visible-sm visible-xs m-t-20">
					<a href="/impressum">Impressum</a>
				</div>
			</div>
			<!-- END MENU ITEMS -->
		</div>
	</div>
</nav>
<!-- END HEADER -->

<!-- BEGIN JUMBOTRON -->
<section class="jumbotron demo-custom-height xs-full-height bg-black" data-pages-bg-image="/assets-frontend/images/hero_4.jpg" style="height: 100%">
	<div class="container-xs-height full-height">
		<div class="col-xs-height col-middle text-left">
			<div class="container">
				<div class="col-sm-6">
					<h1 class="light text-white"><b>Dein careertag</b></h1>
					<h4 class="text-white">Nur noch einen kleinen Schritt entfernt.</h4>
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Fehler:</strong> Bitte Eingabefelder prüfen.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<form class="m-t-25 m-b-20" action="/auth/register" method="post">
						{!! csrf_field() !!}
						<div class="col-md-6" style="padding: 0;">
							<div class="form-group form-group-default input-group no-border input-group-attached col-md-12  col-sm-12 col-xs-12">
								<label>Vorname</label>
								<input type="text" class="form-control" placeholder="Max" name="firstname" value="{{ old('firstname') }}" autocomplete="off">
							</div>
						</div>
						<div class="col-md-6" style="padding: 0; padding-left: 15px;">
							<div class="row form-group form-group-default input-group no-border input-group-attached col-md-12  col-sm-12 col-xs-12">
								<label>Nachname</label>
								<input type="text" class="form-control" placeholder="Mustermann" name="lastname" value="{{ old('lastname') }}" autocomplete="off">
							</div>
						</div>
						<div class="row form-group form-group-default input-group no-border input-group-attached col-md-12  col-sm-12 col-xs-12">
							<label>E-Mail Adresse</label>
							<input type="email" class="form-control" placeholder="max@mustermann.tdl" name="email" value="{{ old('email') }}" autocomplete="off">
						</div>
						<div class="col-md-6" style="padding: 0;">
							<div class="form-group form-group-default input-group no-border input-group-attached col-md-12  col-sm-12 col-xs-12">
								<label>Passwort</label>
								<input type="password" class="form-control" placeholder="" name="password" autocomplete="off">
							</div>
						</div>
						<div class="col-md-6" style="padding: 0; padding-left: 15px;">
							<div class="row form-group form-group-default input-group no-border input-group-attached col-md-12  col-sm-12 col-xs-12">
								<label>Passwort Wiederholung</label>
								<input type="password" class="form-control" placeholder="" name="password_confirmation" autocomplete="off">
							</div>
						</div>
						<button class="btn btn-block btn-lg btn-black btn-cons" style="background: #20a765; border-color: #20a765; font-weight: bold;" type="submit">REGISTRIEREN</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END JUMBOTRON -->

<!-- BEGIN CORE FRAMEWORK -->
<script src="/assets-frontend/plugins/pace/pace.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/pages-frontend/js/pages.image.loader.js"></script>
<script type="text/javascript" src="/assets-frontend/plugins/jquery/jquery-1.11.1.min.js"></script>
<!-- BEGIN RETINA IMAGE LOADER -->
<script type="text/javascript" src="/assets-frontend/plugins/jquery-unveil/jquery.unveil.min.js"></script>
<!-- END VENDOR JS -->
<!-- BEGIN PAGES FRONTEND LIB -->
<script type="text/javascript" src="/pages-frontend/js/pages.frontend.js"></script>
<!-- END PAGES LIB -->
</html>
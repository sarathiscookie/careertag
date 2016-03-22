<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>careertag von {{$firstname}} {{$lastname}}</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

    <link href="/assets-frontend/plugins/font-awesome/css/font-awesome.min.css?v=1234" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="/css/jquery-ui.min.css">
    <link rel="stylesheet" href="/pages/css/jquery.taghandler.css">
    <link rel="stylesheet" href="/pages/css/dropzone.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <link href="/css/html5imageupload.css" rel="stylesheet">
</head>
<body>

<div class="container" style="position: relative; padding-top: 40px; padding-bottom:150px;">
    <img src="/assets-frontend/images/logo-ct.png" style="position:absolute; right: 15px;">

    <a href="/auth/logout" class="btn btn-sm  btn-rounded btn-default"><span class="glyphicon glyphicon-user"></span> Ausloggen</a>
    <a href="/<?php if (Auth::check()) { echo Auth::user()->alias; } ?>" target="_blank" class="btn btn-sm  btn-rounded btn-default"><span class="glyphicon glyphicon-th-list"></span> Öffentliches Profil anzeigen</a>
    <form style="display: inline;" class="form-horizontal" role="form" method="POST" action="/<?php if (Auth::check()) { echo Auth::user()->alias; } ?>/laravelsnappy/<?php if (Auth::check()) { echo md5(Auth::user()->alias); } ?>/<?php if (Auth::check()) { echo Auth::user()->id; } ?>">
      {!! csrf_field() !!}
        <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-save-file"></span> PDF-Export</button>
    </form>
    @if ($confirmTime)
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
            <h4 class="text-center">Welcome {{ Auth::user()->firstname }}!!!
                <small>Please check your email and confirm to publish your profile for public!</small>
            </h4>
        </div>
    @endif
    <br><br>
    <div class="row">
        <div class="col-md-5">
            <section id="sec_contact">
                <div class="tag-cat">#CONTACT <a tabindex="0" class="" role="button" data-toggle="popover" data-trigger="focus" data-content="Deine Kontaktdaten sind nur für registrierte Arbeitgeber sichtbar."><span class="glyphicon glyphicon-info-sign"></span></a></div>
                <br>
                <input id="firstname" class="tag ct-input" value="{{$firstname}}" placeholder="{{ trans('ct.firstname') }}" readonly>
                <input id="lastname" class="tag ct-input" value="{{$lastname}}" placeholder="{{ trans('ct.lastname') }}" readonly><br>
                <input id="city" class="tag ct-input" value="{{$city}}" placeholder="{{ trans('ct.city') }}"><br>
                <input id="webpage" style="width: 81%;" class="tag ct-input" value="{{$webpage}}" placeholder="Webprofil"><br>
                <br>
                <div style="padding: 20px; background: #F0F0F0; width: 80%;">
                    <strong>Kontaktdaten für den Arbeitgeber</strong><br>
                    <input id="birthdate" class="tag ct-input" value="{{$birthdate}}" placeholder="{{ trans('ct.birthdate') }}" data-inputmask="'alias': 'dd.mm.yyyy'"><br>
                    <input id="email" style="width: 100%;" class="tag ct-input" value="{{$email}}" placeholder="{{ trans('ct.mail') }}" readonly><br>
                    <input id="phone" style="width: 100%;" class="tag ct-input" value="{{$phone}}" placeholder="{{ trans('ct.phone') }}"><br>
                    <br>
                    <form id="uploadCertificate" name="uploadCertificate" action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label for="certificate">Zeugnisse <a tabindex="0" class="" style="color: #333;" role="button" data-toggle="popover" data-trigger="focus" data-content="Alle Zeugnisse in eine PDF zusammenführen. Maximal 5MB."><span class="glyphicon glyphicon-info-sign"></span></a></label>
                            <div class="loadContactCertificate"></div>
                            <input id="certificate" type="file" name="certificate" class="filestyle pdfupload" data-input="false" data-iconName="glyphicon glyphicon-open-file" data-buttonText="PDF hochladen"/>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        <div class="col-md-7">
            <section id="profile">
                <div id="profile_image" class="dropzone smalltext" data-removeurl="/deleteProfile/image" @if($userthumb=='Profile') data-image="{{route('viewprofileimage')}}" @endif data-button-edit="false" data-width="300" data-height="300" data-originalsize="false" data-url="/uploadProfile/image" style="border-radius: 150px;">
                    <input type="file" name="thumb" style="-webkit-border-radius: 150px;-moz-border-radius: 150px;border-radius: 150px;"/>
                </div>

                <div id="sum_experience">experience<br><span>{{ $expyears }}</span><br><b>years</b></div>

                <div id="graph">
                    <div id="bar1" class="bar">
                        <div class="caption">{{ trans('ct.presentation') }}</div>
                        <div style="height: 70px; margin-left: 150px; position: relative;">
                            <div class="btn-group btn-group-xs" role="group" aria-label="..." style="position:absolute; top: 27px; left:20px; z-index:1000;">
                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span></button>
                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-right"></span></button>
                            </div>
                            <div id="bar1_slider" class="slider" style="width: {{ $presentation.'%' }}">

                            </div>
                        </div>
                    </div>
                    <div id="bar2" class="bar">
                        <div class="caption">{{ trans('ct.conception') }}</div>
                        <div style="height: 70px; margin-left: 150px; position: relative;">
                            <div class="btn-group btn-group-xs" role="group" aria-label="..." style="position:absolute; top: 27px; left:20px; z-index:1000;">
                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span></button>
                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-right"></span></button>
                            </div>
                            <div id="bar2_slider" class="slider" style="width: {{ $conception.'%' }}">

                            </div>
                        </div>
                    </div>
                    <div id="bar3" class="bar">
                        <div class="caption">{{ trans('ct.management') }}</div>
                        <div style="height: 70px; margin-left: 150px; position: relative;">
                            <div class="btn-group btn-group-xs" role="group" aria-label="..." style="position:absolute; top: 27px; left:20px; z-index:1000;">
                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span></button>
                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-right"></span></button>
                            </div>
                            <div id="bar3_slider" class="slider" style="width: {{ $management.'%' }}">

                            </div>
                        </div>
                    </div>
                    <div id="bar4" class="bar">
                        <div class="caption">{{ trans('ct.creativity') }}</div>
                        <div style="height: 70px; margin-left: 150px; position: relative;">
                            <div class="btn-group btn-group-xs" role="group" aria-label="..." style="position:absolute; top: 27px; left:20px; z-index:1000;">
                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span></button>
                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-arrow-right"></span></button>
                            </div>
                            <div id="bar4_slider" class="slider" style="width: {{ $creativity.'%' }}">

                            </div>
                        </div>
                    </div>
                </div>
                <div id="graph_function">
                    {!! $abilitytestbutton !!}
                </div>
            </section>
            <!-- Modal for ability test-->


            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="myModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div id="abilityQuestions"></div>
                    </div>
                </div>
            </div>
            <!-- Modal End -->

            <br><br><a href="http://careertag.liebt.hosting/Elena.Fritz_696" target="_blank"><img src="/assets/img/example-ct.jpg" class="img-responsive pull-right"></a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <section>
                <div class="tag-cat">#SEARCH <a tabindex="0" class="" role="button" data-toggle="popover" data-trigger="focus" data-content="Du kannst mehrere Städte angeben."><span class="glyphicon glyphicon-info-sign"></span></a></div>
                <br>
                <div id="user_city">{!! $searchcity !!}</div>
                <br>
                <div>
                    <label for="position">{{ trans('ct.position') }}</label>
                    <ul class="tag-handler" data-category="4"></ul>
                </div>
            </section>
        </div>
        <div class="col-md-7">
            <section>
                <div class="tag-cat">#KEYSKILLS <a tabindex="0" class="" role="button" data-toggle="popover" data-trigger="focus" data-content="Welche Zusatzqualifikationen und Fähigkeiten zeichnen dich aus?"><span class="glyphicon glyphicon-info-sign"></span></a></div>
                <br>
                <ul class="tag-handler" data-category="1">
                </ul>
            </section>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-5">
            <section>
                <div class="tag-cat">#PROFESSION <a tabindex="0" class="" role="button" data-toggle="popover" data-trigger="focus" data-content="Gib deine Abschlüße an."><span class="glyphicon glyphicon-info-sign"></span></a></div>
                <div id="profession_handler" title="Berufe bearbeiten" class="glyphicon glyphicon-edit" style="cursor: pointer; display: none; margin-left: 15px;"></div>
                <br>
                <div id="profession_container"></div>
                <!-- Profession modal -->
                <div id="user_professions">
                    <div id="profession_modal" class="modal-dialog" style="margin: 5px 0; z-index: 900; width: 100%;"></div>
                </div>
            </section>
        </div>
        <div class="col-md-7">
            <section>
                <div class="tag-cat">#LANGUAGES <a tabindex="0" class="" role="button" data-toggle="popover" data-trigger="focus" data-content="Welche Sprachen sprichst du wie gut bist du darin?"><span class="glyphicon glyphicon-info-sign"></span></a></div>
                <div id="language_handler" title="Sprachen bearbeiten" class="glyphicon glyphicon-edit" style="cursor: pointer; margin-left: 15px;"></div>
                <br>
                <!-- Default Language-->
                <div id="hidedefaultlanguage" class="loadDefaultUserLanguage">

                </div>
                <!-- Add or Edit language -->
                <div id="languagemodal">
                    <div class="modal-dialog" style="margin: 5px 0;"></div>
                </div>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <section>
                <div class="tag-cat">#EXPERIENCE <a tabindex="0" class="" role="button" data-toggle="popover" data-trigger="focus" data-content="Gib deine wichtigsten Stationen an und beginne mit den ältesten."><span class="glyphicon glyphicon-info-sign"></span></a></div>
                <br>
                <div id="user_experience">{!! $experience !!}</div>
            </section>
        </div>
        <div class="col-md-7">
            <section>
                <div class="tag-cat">#COMPANY <a tabindex="0" class="" role="button" data-toggle="popover" data-trigger="focus" data-content="Unternehmen sind deine Referenzen."><span class="glyphicon glyphicon-info-sign"></span></a></div>
                <br>
                <div id="user_company">{!! $company !!}</div>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <section>
                <div class="tag-cat">#INTERESTS <a tabindex="0" class="" role="button" data-toggle="popover" data-trigger="focus" data-content="Hier kannst du deinen Sport oder dein Ehrenamt nennen."><span class="glyphicon glyphicon-info-sign"></span></a></div>
                <br>
                <ul class="tag-handler" data-category="2">
                </ul>
            </section>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="/pages/js/jquery.taghandler.js"></script>
<script src="/pages/js/dropzone.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>
<script src="/assets/js/jquery.inputmask.bundle.min.js"></script>
<script src="/js/bootstrap-filestyle.min.js"></script>
<script src="/js/html5imageupload.min.js"></script>
<script src="/js/jquery.form.min.js"></script>
<script src="/js/all.js"></script>
</body>
</html>
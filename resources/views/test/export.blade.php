<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>careertag von </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="/pages/css/jquery.taghandler.css">
    <link rel="stylesheet" href="/pages/css/dropzone.css">
    <link rel="stylesheet" href="/css/app.css">

</head>
<body class="public-view"><br><br><br>
<div class="container" style="position: relative; padding-top: 70px;">
    <img src="/assets-frontend/images/logo-ct.png" style="position:absolute; top:0; right: 15px;">
    <div class="row">
        <div class="col-xs-5">
            <section>
                <div class="tag-cat">#CONTACT</div><br>
                <div class="tag">{{$getUserDetails["firstname"]}} {{$getUserDetails["lastname"]}}</div> @if($getUserDetails["age"]!=0) <div class="tag">{{$getUserDetails["age"]}}</div> @endif
                @if($getUserDetails["birthdate"]!="")<br><div class="tag">{{$getUserDetails["birthdate"]}}</div> @endif
                <br><div class="tag">{{$getUserDetails["email"]}}</div>
                @if($getUserDetails["phone"]!="")<br><div class="tag">{{$getUserDetails["phone"]}}</div>@endif
                @if($getUserDetails["city"]!="")<br><div class="tag">{{$getUserDetails["city"]}}</div>@endif
                @if($getUserDetails["webpage"]!="") <br><div class="tag">{{$getUserDetails["webpage"]}}</div> @endif
                @if($getUserDetails["certificate"]!="") <br><div class="tag"><a href="{{$getUserDetails["certificate"]}}">Zeugnisse herunterladen</a></div> @endif
            </section>
        </div>
        @if($privacy_image=='show')
            <div class="col-xs-7">

                <section id="profile">
                    <div id="profile_image" style="background:  transparent url('{{$profileimg['route']}}') no-repeat scroll center center;"></div>
                    <div id="sum_experience">experience<br><span>{{ $expyears }}</span><br><b>years</b></div>
                    <div id="graph">
                        <div id="bar1" class="bar">
                            <div class="caption">presentation</div>
                            <div id="bar1_slider" class="slider" @if($graphdata['graph_1']>0)style="width:{{$graphdata['graph_1']*20}}%" @endif ></div>
                        </div>
                        <div id="bar2" class="bar">
                            <div class="caption">conception</div>
                            <div id="bar2_slider" class="slider" @if($graphdata['graph_2']>0)style="width:{{$graphdata['graph_2']*20}}%" @endif></div>
                        </div>
                        <div id="bar3" class="bar">
                            <div class="caption">management</div>
                            <div id="bar3_slider" class="slider" @if($graphdata['graph_3']>0)style="width:{{$graphdata['graph_3']*20}}%" @endif></div>
                        </div>
                        <div id="bar4" class="bar">
                            <div class="caption">creativity</div>
                            <div id="bar4_slider" class="slider" @if($graphdata['graph_4']>0)style="width:{{$graphdata['graph_4']*20}}%" @endif></div>
                        </div>
                    </div>
                </section>

            </div>
        @endif
    </div>
    <div class="row">
        @if($privacy_cat_search=='show')
            <div class="col-xs-5">
                <section>
                    <div class="tag-cat">#SEARCH</div><br>
                    @foreach($searchcity as $city)
                        <div class="tag"> {{ ($city->search_city!="")? $city->search_city: $city->search_string }} </div>
                    @endforeach
                    <br clear="all"><br clear="all"><label for="position">{{ trans('ct.position') }}</label><br>
                    @foreach($usertags as $value)
                        @if($value->tagcategory_id==4)
                            <div class="tag">{{ $value->title_en }}</div>
                        @endif
                    @endforeach
                </section>
            </div>
        @endif
        <div class="col-xs-7">
            <section>
                <div class="tag-cat">#KEYSKILLS</div><br>
                @foreach($usertags as $value)
                    @if($value->tagcategory_id==1)
                        <div class="tag">{{ $value->title_en }}</div>
                    @endif
                @endforeach
            </section>
        </div>
    </div>
    <hr>
    <div class="row">
        @if($privacy_cat_ambition=='show')
            <div class="col-xs-5">
                <section>
                    <div class="tag-cat">#PROFESSION</div><br>
                    @foreach($professions as $profession)
                        <div class="tag">{{ $profession->graduation }}</div>
                        @if($profession->subject!="")<div class="tag">{{ $profession->subject }}</div> @endif
                        @if($profession->grade!="0.0")<div class="tag">{{ $profession->grade }}</div> @endif <br>
                    @endforeach
                </section>
            </div>
        @endif
        @if($privacy_cat_languages=='show')
            <div class="col-xs-7">
                <section>
                    <div class="tag-cat">#LANGUAGES</div><br>
                    @foreach($userlang as $language)
                        <div class="col-xs-8" style="background-color: lightgrey; background-repeat: repeat; padding: 0px; margin-top: 5px;">
                            <div style="color: #FFF; background-color:{{ $langcolor[$language->ranking-1] }}; width:{{ $language->ranking * 25 ."%" }}; padding: 8px;">
                                {{ $language->title_de }}
                            </div>
                        </div>

                    @endforeach
                </section>
            </div>
        @endif
    </div>
    <div class="row">
        @if($privacy_cat_experience=='show')
            <div class="col-xs-5">
                <section>
                    <div class="tag-cat">#EXPERIENCE</div><br>
                    @foreach($experiences as $experience)
                        <div class="tag">{{ $experience->title }}</div> <div class="tag">{{ $experience->years }} </div> <br>
                    @endforeach
                </section>
            </div>
        @endif
        @if($privacy_cat_company=='show')
            <div class="col-xs-7">
                <section>
                    <div class="tag-cat">#COMPANY</div><br>
                    @foreach($experiences as $experience)
                        <div class="tag">{{ $experience->company }}</div> <div class="tag">{{ $experience->city }}</div> <br>
                    @endforeach
                </section>
            </div>
        @endif
    </div>
    <div class="row">
        @if($privacy_cat_interests=='show')
            <div class="col-xs-12">
                <section>
                    <div class="tag-cat">#INTERESTS</div><br>
                    @foreach($usertags as $value)
                        @if($value->tagcategory_id==2)
                            <div class="tag">{{ $value->title_en }}</div>
                        @endif
                    @endforeach
                </section>
            </div>
        @endif
    </div>
    <?php
        $url = explode("/", $_SERVER["REQUEST_URI"]);
    ?>
    <br><br>careertag online verfügbar unter <a href="http://<?=$_SERVER["SERVER_NAME"] . '/' . $url[1]?>">http://<?=$_SERVER["SERVER_NAME"] . '/' . $url[1]?></a>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
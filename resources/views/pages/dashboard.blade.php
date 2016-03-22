<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>test</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="pages/css/jquery.taghandler.css">
    <link rel="stylesheet" href="pages/css/dropzone.css">
    <style>
        ::-webkit-input-placeholder { /* WebKit, Blink, Edge */
            color:    #FFF;
        }
        :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
            color:    #FFF;
            opacity:  1;
        }
        ::-moz-placeholder { /* Mozilla Firefox 19+ */
            color:    #FFF;
            opacity:  1;
        }
        :-ms-input-placeholder { /* Internet Explorer 10-11 */
            color:    #FFF;
        }

        SECTION {
            margin-bottom: 20px;
        }
        .tag-cat {
            background: forestgreen;
            padding: 5px 10px;
            margin: 5px;
            display: inline-block;
            font-size: 15px;
            font-weight: bold;
            color: #FFF;
        }

        .tag {
            background: darkgrey;
            padding: 5px 10px;
            margin: 2px 5px;
        }

        input {
            border: none;
            background: none;
            color: #FFF;
            box-shadow: none;
        }

    </style>
</head>
<body>
<a href="/auth/logout" class="clearfix">
    <span class="pull-left">Logout</span>
    <span class="pull-right"><i class="pg-power"></i></span>
</a>
<div class="container">
    @if ($confirmTime === NULL)
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
            <h4 class="text-center">Welcome {{ Auth::user()->firstname }}!!! <small>Please check your email and confirm to publish your profile for public!</small></h4>
        </div>
    @endif
    <div class="row">
        <div class="col-md-4 col-xs-4">
            <section>
                <div class="tag-cat">#CONTACT</div><br>
                <input id="firstname" class="tag ct-input" placeholder="{{ trans('ct.firstname') }}"><input id="lastname" class="tag ct-input" placeholder="{{ trans('ct.lastname') }}"><br>
            </section>

            <section>
                <div class="tag-cat">#SEARCH</div><br>
                <input id="firstname" class="tag ct-input" placeholder="Vorname">
            </section>
        </div>
        <div class="col-md-8 col-xs-8">
            <section>
                <div class="tag-cat">#Profile Pic</div><br>
                <!--<div class="dropzone" id="dropzoneId"></div>-->
                <?php
                $id = 1;
                ?>
                <form action="/uploadProfile/image/{{$id}}" class="dropzone" id="my-awesome-dropzone" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <img src="{{route('viewprofileimage', $id)}}" alt="Avatar" class="img-responsive" id="avatar" width="200" height="200"/>
                <span class="glyphicon glyphicon-remove" aria-hidden="true" id="deleteProfileImage"></span>
            </section>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4 col-xs-4">
            <section>
                <div class="tag-cat">#PROFESSION</div><br>
                <input id="firstname" class="tag ct-input" placeholder="Vorname">
            </section>

            <section>
                <div class="tag-cat">#EXPERIENCE</div><br>
                <input id="firstname" class="tag ct-input" placeholder="Vorname">
            </section>
        </div>
        <div class="col-md-8 col-xs-8">
            <section>
                <div class="tag-cat">#LANGUAGES</div><br>
                <ul class="tag-handler-language">
                </ul>
            </section>

            <section>
                <div class="tag-cat">#COMPANY</div><br>
                <input id="firstname" class="tag ct-input" placeholder="Vorname">
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <section>
                <div class="tag-cat">#KEYSKILLS</div><br>
                <ul class="tag-handler" data-category="1">
                </ul>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <section>
                <div class="tag-cat">#INTERESTS</div><br>
                <ul class="tag-handler" data-category="2">
                </ul>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <section>
                <div class="tag-cat">#AMBITION</div><br>
                <ul class="tag-handler-ambition">
                </ul>
            </section>
        </div>
    </div>
</div>


<div id="privacy_firstname" class="ct-privacy-field">
    <img src="http://tympanus.net/PausePlay/images/play.png" width="60px" height="60px"/>
    <img src="http://maraa.in/wp-content/uploads/2011/09/pause-in-times-of-conflict.png" width="60px" height="60px" style="display:none"/>
</div>
<div id="result"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="pages/js/jquery.taghandler.js"></script>
<script src="pages/js/dropzone.js"></script>
<script>
    var ct_id = 1000;
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script type="text/javascript">
    /* Tag Ambition */
    $(".tag-handler-ambition").tagHandler({
        getData: { userID: 1, tagcategoryid: 3},
        getURL: '/tag/ambition',
        onAdd: function(tag) {
            $.post("/ambition/insert", {tag: tag, userID: 1, createdby: '1', tagcategoryid: 3},
                    function (response) {
                        $( ".tag-handler-ambition" ).after('<form action="" method="post" enctype="multipart/form-data" id="addForm_'+response.id+'" ><input type="hidden" name="_token" value="{{ csrf_token() }}"><span id="tag-ambition-append_'+response.id+'">'+response.tagambition+'</span><input name="file" type="file" id="file_'+response.id+'" ><input name="hiddenid" id="hiddenid_'+response.id+'" type="hidden" value='+response.id+' ><button type="submit" class="btn btn-default" id="submit_'+response.id+'" >Submit</button></form>');
                        /* Upload files of ambition tags begin*/
                        $("#addForm_"+response.id).submit(function(e){
                            e.preventDefault();
                            var form      = $("#addForm_"+response.id)[0];
                            var formdata  = new FormData(form);
                            formdata.append('file', 'file_'+response.id);
                            $.ajax({
                                url : "/ambition/update",
                                type: "POST",
                                data : formdata,
                                processData: false,
                                contentType: false,
                                success:function(data){
                                    $( "#addForm_"+response.id ).replaceWith( $( '<div id="filename-response_'+response.id+'">'+data.filename+'</div>' ) );
                                }
                            });
                            e.preventDefault();
                        });
                        /* Upload files of ambition tags end*/
                    });
        },
        afterDelete: function(tag) {
            $.post("/ambition/delete", {tag: tag, userID: 1, tagcategoryid: 3},
                    function (html) {
                        $( "#addForm_"+html.tagambitionID).hide();
                        $( "#filename-response_"+html.tagambitionID).hide();
                    });
        },
        autocomplete: true,
        autoUpdate: true
    });

    /* Tag handler INTEREST KEYSKILLS  */
    $(".tag-handler").each(function(i, v){
        $(this).tagHandler({
            getData: { tagcategoryid: $(v).data('category')  },
            getURL: '/tag/interest',
            /*updateData: { tagcategoryid: '1', createdby: '1' },
             updateURL: '/interest/update',*/
            onAdd: function(tag) {
                //alert('Added tag: ' + tag);
                $.post("/interest/update", {tag: tag, tagcategoryid: $(v).data('category'), createdby: '1', userID: 1},
                        function (html) {
                        });
            },
            afterDelete: function(tag) {
                //alert('Deleted tag: ' + tag);
                $.post("/interest/delete", {tag: tag, tagcategoryid: $(v).data('category')},
                        function (html) {
                        });
            },
            autocomplete: true,
            autoUpdate: true
        });
    });

    /* Tag Language */
    $(".tag-handler-language").tagHandler({
        getData: { userID: '1' },
        getURL: '/tag/language',
        allowAdd: false,
        onAdd: function(tag) {
            $.post("/language/insert", {tag: tag, userID: '1'},
                    function (response) {
                        //$( ".tag-language" ).show().fadeIn('slow');
                        $( ".tag-handler-language" ).after('<div id="tag-language_'+response.id+'"><span id="tag-language-append_'+response.id+'">'+response.taglanguage+'</span><div id="slider_'+response.id+'" style="width:30%;"></div></div>');
                        $( "#slider_"+response.id ).slider({
                            min: 0,
                            max: 5,
                            step: 1,
                            /*value: 3,*/
                            change: function( event, ui ) {
                                //alert(response.id);
                                $.post("/language/update", {id: response.id, ranking: ui.value},
                                        function (html) {
                                        });
                            }
                        });
                    });
        },
        afterDelete: function(tag) {
            $.post("/language/delete", {tag: tag, userID: '1'},
                    function (html) {
                        $( "#tag-language_"+html.taglanguageID).hide();
                    });
        },
        autocomplete: true,
        autoUpdate: true
    });
</script>
<script>
    $( ".ct-input" ).change(function() {
        $.post( "/update/input", {id:ct_id, field:$(this).attr('id'), value:$(this).val()})
                .done(function(msg) {
                    $('#result').html(msg);
                })
                .fail(function() {
                    alert( "error" );
                })
                .always(function() {

                });
    });

    $('.ct-privacy-field').click(function() {
        field_id = '#' + $(this).attr('id');
        $.post( "/update/privacy", {id:ct_id, type: 'field', field:$(this).attr('id')})
                .done(function(msg) {
                    $(field_id).find('img').toggle();
                    $('#result').html(msg);
                })
                .fail(function() {
                    alert( "error" );
                })
                .always(function() {

                });
    });
</script>
<script>
    $(function(){
        $.get("/getDetails", { id: 1 })
                .done(function( data ) {
                    if(data.filename == 'Profile'){
                        $(".dropzone").css('background-image', 'none');
                        $(".dropzone").hide();
                    }
                    else{
                        $(".dropzone").css('background-image', 'url(/pages/img/profilepic.jpg)');
                        $("#deleteProfileImage").hide();
                    }
                });

        $("#my-awesome-dropzone").click(function(){
            $(".dropzone").css('background-image', 'none');
            $("#deleteProfileImage").show();
        });
        $("#deleteProfileImage").click(function(){
            $.post("/deleteProfile/image", { id: 1 })
                    .done(function( data ) {
                        $("#avatar").hide();
                        $(".dropzone").show(function(){
                            $("#deleteProfileImage").hide();
                        });
                        $(".dropzone").css('background-image', 'url(/pages/img/profilepic.jpg)');
                    });
        });

    })
</script>
</body>
</html>
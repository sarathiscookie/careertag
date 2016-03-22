<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Help</a></li>
            </ul>
            <form class="navbar-form navbar-right">
                <input type="text" class="form-control" placeholder="Search...">
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li class="active"><a href="#">Overview <span class="sr-only">(current)</span></a></li>
                <li><a href="#">Reports</a></li>
                <li><a href="#">Analytics</a></li>
                <li><a href="#">Export</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="">Nav item</a></li>
                <li><a href="">Nav item again</a></li>
                <li><a href="">One more nav</a></li>
                <li><a href="">Another nav item</a></li>
                <li><a href="">More navigation</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="">Nav item again</a></li>
                <li><a href="">One more nav</a></li>
                <li><a href="">Another nav item</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Dashboard</h1>

            <div data-category="1" class="tags"><h4>Keyskills</h4></div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead id="keyskills">
                    <tr>
                        <th>#</th>
                        <th>German Title</th>
                        <th>English Title</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Toggle</th>
                    </tr>
                    </thead>
                </table>
            </div>

            <div data-category="2" class="tags"><h4>Interests</h4></div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead id="interests">
                    <tr>
                        <th>#</th>
                        <th>German Title</th>
                        <th>English Title</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Toggle</th>
                    </tr>
                    </thead>
                </table>
            </div>

            <div data-category="3" class="tags"><h4>Professions</h4></div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead id="professions">
                    <tr>
                        <th>#</th>
                        <th>German Title</th>
                        <th>English Title</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Toggle</th>
                    </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="/js/ie10-viewport-bug-workaround.js"></script>

<script>
    /* Getting tag: KEYSKILLS INTEREST AMBITION begins */
    $(function() {
        $( ".tags" ).each(function (i, v) {
            var tagcategory = $(v).data('category');
            $.get("/backend/tags", {tagcategoryid: tagcategory}, function(response){
                var keynum  = 1;
                var profnum = 1;
                var intenum = 1;
                for (var i = 0; i < response.Tags.length; i++) {
                    if(response.Tags[i].created_by == 0){
                        firstname  = 'Admin';
                        lastname   = 'User';
                    }
                    else{
                        firstname  = response.Tags[i].firstname;
                        lastname   = response.Tags[i].lastname;
                    }

                    if(response.Tags[i].suggestion == 'yes'){
                      var classON    = 'btn btn-sm btn-primary active';
                      var classOFF   = 'btn btn-sm btn-default';
                    }
                    if(response.Tags[i].suggestion == 'no'){
                        var classOFF = 'btn btn-sm btn-primary active';
                        var classON  = 'btn btn-sm btn-default';
                    }
                    var ToggleID = response.Tags[i].id;
                    if(response.Tags[i].tagcategory_id == 1){
                        $( "#keyskills" ).after('<tbody><tr><td>'+keynum+++'</td><td>'+response.Tags[i].title_de+'</td><td>'+response.Tags[i].title_en+'</td><td><a href="/'+response.Tags[i].alias+'" target="_blank">'+firstname+' '+lastname+'</a></td><td>'+response.Tags[i].created_at+'</td><td><div class="btn-group btn-toggle_'+response.Tags[i].id+'" data-tagid="'+response.Tags[i].id+'" data-suggestion="'+response.Tags[i].suggestion+'"><button class="'+classON+'">ON</button><button class="'+classOFF+'">OFF</button></div></td></tr></tbody>')
                    }
                    if(response.Tags[i].tagcategory_id == 2){
                        $( "#interests" ).after('<tbody><tr><td>'+intenum+++'</td><td>'+response.Tags[i].title_de+'</td><td>'+response.Tags[i].title_en+'</td><td><a href="/'+response.Tags[i].alias+'" target="_blank">'+firstname+' '+lastname+'</a></td><td>'+response.Tags[i].created_at+'</td><td><div class="btn-group btn-toggle_'+response.Tags[i].id+'" data-tagid="'+response.Tags[i].id+'" data-suggestion="'+response.Tags[i].suggestion+'"><button class="'+classON+'">ON</button><button class="'+classOFF+'">OFF</button></div></td></tr></tbody>')
                    }
                    if(response.Tags[i].tagcategory_id == 3){
                        $( "#professions" ).after('<tbody><tr><td>'+profnum+++'</td><td>'+response.Tags[i].title_de+'</td><td>'+response.Tags[i].title_en+'</td><td><a href="/'+response.Tags[i].alias+'" target="_blank">'+firstname+' '+lastname+'</a></td><td>'+response.Tags[i].created_at+'</td><td><div class="btn-group btn-toggle_'+response.Tags[i].id+'" data-tagid="'+response.Tags[i].id+'" data-suggestion="'+response.Tags[i].suggestion+'"><button class="'+classON+'">ON</button><button class="'+classOFF+'">OFF</button></div></td></tr></tbody>')
                    }
                    $( ".btn-toggle_"+ToggleID ).click(function() {
                        tagsID       = $( this ).data('tagid');
                        suggestionID = $( this ).data('suggestion');
                        $(this).find('.btn').toggleClass('active');
                        if ($(this).find('.btn-primary').size()>0) {
                            $(this).find('.btn').toggleClass('btn-primary');
                        }
                        $(this).find('.btn').toggleClass('btn-default');
                        $.get("/toggle/update", {toggleTags: tagsID, toggleSuggestion: suggestionID}, function(response){
                        });
                    });
                }
            });
        });
    });
    /* Getting tag: KEYSKILLS INTEREST AMBITION begins */
</script>
</body>
</html>

var ct_id = 1000;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/* Graph slider for each category(Presentation, Conception, Management, Creativity)*/
/*$("#graph .slider").ready(function () {
    $.get("/graph/ranking", function (response) {
        if (response.graph_ranking.graph_1 == 0) {
            var rank1 = "25";
            $("#bar1_slider").width(rank1 + '%');
        }
        else {
            var rank1 = response.graph_ranking.graph_1 * 25;
            $("#bar1_slider").width(rank1 + '%');
        }
        if (response.graph_ranking.graph_2 == 0) {
            var rank2 = "25";
            $("#bar2_slider").width(rank2 + '%');
        }
        else {
            var rank2 = response.graph_ranking.graph_2 * 25;
            $("#bar2_slider").width(rank2 + '%');
        }
        if (response.graph_ranking.graph_3 == 0) {
            var rank3 = "25";
            $("#bar3_slider").width(rank3 + '%');
        }
        else {
            var rank3 = response.graph_ranking.graph_3 * 25;
            $("#bar3_slider").width(rank3 + '%');
        }
        if (response.graph_ranking.graph_4 == 0) {
            var rank4 = "25";
            $("#bar4_slider").width(rank4 + '%');
        }
        else {
            var rank4 = response.graph_ranking.graph_4 * 25;
            $("#bar4_slider").width(rank4 + '%');
        }
    });
});*/
/*
$("#graph .slider").resizable({
    animateDuration: 0,
    animateEasing: "easeOutBounce",
    helper: "ui-resizable-helper",
    animate: true,
    handles: 'w',
    maxWidth: '100%',
    grid: [25, 25],
    stop: function () {
        var graph1 = $("#bar1_slider").width() / 25;
        var graph2 = $("#bar2_slider").width() / 25;
        var graph3 = $("#bar3_slider").width() / 25;
        var graph4 = $("#bar4_slider").width() / 25;
        $.post("/graph", {graph1: graph1, graph2: graph2, graph3: graph3, graph4: graph4},
            function (response) {
            });
    }
});*/

/* Contact Section certificate upload and delete Begin*/
/*$("#certificate").click(function(){
   alert("click to upload");
});*/
$( "#uploadCertificate" ).change(function (e) {
    e.preventDefault();
    var form = $("#uploadCertificate")[0];
    var formdata = new FormData(form);
    formdata.append('file', 'certificate');
    $.ajax({
        url: "/contact/certificate",
        type: "POST",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (data) {
            $( ".loadContactCertificate" ).after('<div class="certificateFile-response">Datei erfolgreich hochgeladen.</div>');
        }
    });
});

$.get("/contact/certificate/file", function (response) {
    if(response){
        $( ".loadContactCertificate" ).html('<div class="certificateFile-response"><a class="btn btn-sm btn-default" href="/certificate/download/">Zeugnisse herunterladen</a> <a class="removecertificate" style="color: #333; cursor: pointer;"><i class="fa fa-trash"></i></a></div><br>');
        $( ".removecertificate" ).click(function(){
            $.post("/certificate/file/delete", function(deleteresponse){
                if(deleteresponse){
                    $( ".loadContactCertificate" ).hide();
                }
            });
        });
    }
});
/* Contact Section certificate upload and delete End*/


/* Tag handler INTEREST, KEYSKILLS, POSITION */
$(".tag-handler").each(function (i, v) {
    var limit =10;
    var byuser=true;
    var chars =2;
    if($(v).data('category')==4) {
         limit = 5;
        byuser = false;
        chars  =0;
    }
    $(this).tagHandler({
        getData: {tagcategoryid: $(v).data('category')},
        getURL: '/tag/interest',
        /*updateData: { tagcategoryid: '1', createdby: '1' },
         updateURL: '/interest/update',*/
        onAdd: function (tag) {
            //alert('Added tag: ' + tag);
            $.post("/interest/update", {tag: tag, tagcategoryid: $(v).data('category')},
                function (html) {
                });
        },
        afterDelete: function (tag) {
            //alert('Deleted tag: ' + tag);
            $.post("/interest/delete", {tag: tag, tagcategoryid: $(v).data('category')},
                function (html) {
                });
        },
        autocomplete: true,
        maxTags:limit,
        autoUpdate: true,
        allowAdd:byuser,
        minChars: chars
    });
});

/* Ability Tests Begin*/
$( "#graph_function" ).on('click','#abilitytest',function() {
    $.post("/abilitytest", function(response){
        $( "#abilityQuestions" ).html(response);
        $( ".abilityanswer" ).click(function(){
            abilityanswer     = $(this).attr('id');
            abilityquestionid = $( ".abilityquestionid" ).val();
            $.post("/abilityanswer", {abilityanswer: abilityanswer, abilityquestionid: abilityquestionid}, function(success){
            });
        });

        $(document).on('click', '.pager a', function(e){
            e.preventDefault();
            var pageID = $(this).attr('href').split('page=')[1];
            $.get("/pagination/abilitytest", {page: pageID}, function(response){
                $( "#abilityQuestions" ).html(response);
                $( ".paginationabilityanswer" ).click(function(){
                    abilityanswer     = $(this).attr('id');
                    abilityquestionid = $( ".paginationabilityquestionid" ).val();
                    $.post("/pagination/abilityanswer", {abilityanswer: abilityanswer, abilityquestionid: abilityquestionid}, function(success){
                        if(success.anscount==20) {
                            $('#graph .btn-group').hide();
                            setGraphWidth();
                        }
                    });
                });
            });
        });

    });
});
/* Ability Tests End*/

$("#sec_contact .ct-input").change(function () {
    $.post("/update/input", {id: ct_id, field: $(this).attr('id'), value: $(this).val()})
        .done(function (msg) {
            //$('#result').html(msg);
            if(msg!='')
            alert("Bitte eine gültige URL mit http(s):// einfügen!");
        })
        .fail(function () {
            alert("error");
        })
        .always(function () {

        });
});

$('.ct-privacy-field').click(function () {
    field_id = '#' + $(this).attr('id');
    $.post("/update/privacy", {id: ct_id, type: 'field', field: $(this).attr('id')})
        .done(function (msg) {
            $(field_id).find('img').toggle();
            $('#result').html(msg);
        })
        .fail(function () {
            alert("error");
        })
        .always(function () {

        });
});


function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

Dropzone.autoDiscover = false; // Disabling autoDiscover, otherwise Dropzone will try to attach twice.

/* NEW DROPZONE PROFILE IMG */
$('.dropzone').html5imageupload();

/* OLD DROPZONE PROFILE IMG




$(function () {

    var myDropzone = new Dropzone("#profile_image_dropzone", {
        maxFilesize: 2,
        parallelUploads: 5,
        maxFiles: null,
        dictMaxFilesExceeded: 'hellp'
    });

    myDropzone.on("complete", function(file) {
        $.get("/getDetails")
            .done(function (data) {
                $("#profile_image").css('background-image', 'url(/profile.png)');
                if (data.filename == 'Profile') {
                    $("#profile_image").css('background-image', 'url(' + data.route + '?' + makeid() + ')');
                    $("#deleteProfileImage").show();
                }
            });
    });

    $.get("/getDetails")
        .done(function (data) {
            $("#profile_image").css('background-image', 'url(/profile.png)');
            if (data.filename == 'Profile') {
                $("#profile_image").css('background-image', 'url(' + data.route + ')');
                $("#deleteProfileImage").show();
            }
        });

    $("#deleteProfileImage").click(function () {
        $.post("/deleteProfile/image")
            .done(function (data) {
                $(".dropzone").show(function () {
                    $("#deleteProfileImage").hide();
                });
                $("#profile_image").css('background-image', 'url(/profile.png)');
            });
    });
});

*/

//User profile settings add/get/save
$( document ).ready(function() {
    //Privacy settings save
    $( ".ct-privacy" ).each(function(index) {
        $(this).on("click", function(){
            privacy     = $(this).attr('alt');
            settingname = $(this).attr('id');

            $.post( "/profile/privacy", { param_set : settingname, param_val: privacy })
                .done(function( data ) {
                    if (data == "show")
                        $('#'+settingname).attr('src','/assets/img/privacy-show.png').attr('alt',data);
                    else
                        $('#'+settingname).attr('src','/assets/img/privacy-hide.png').attr('alt',data);
                });
        });
    });
});

//Experience/Company Inputs
$( '#user_experience' ).ready(function() {
    $("section").on('change', '.ct-ue-input', function () {
        var inputitem = $(this).attr('id').split("_");
        if(inputitem[0]!="city"){
            $.post("/experience/update", {field: $(this).attr('id'), value: $(this).val()}, function (data) {
                if (data.id && data.id > 0) {
                    $(".ct-ue-input").each(function (index) {
                        if($(this).attr('id').split("_")[0]!="city") {
                            id = $(this).attr('id').replace("_0", "_" + data.id);
                            $(this).attr('id', id);
                        }
                    });
                }
                else {
                    if (data.experience != "" && $('#years_0').length == 0) {
                        $("#user_experience").prepend(data.experience);
                        $("#user_company").prepend(data.company);
                    }
                    if (data.action != '' && $('#' + data.action_for).length == 0)
                        $(data.action).insertAfter($('#company_'+ data.action_for).next('.city-api'));
                    if(data.exp_years) {
                        $('#sum_experience span').text(data.exp_years);
                    }
                }
            }, "json");
        }
    });
    //Update position
    $("section").on('change', '.ct-ue-select', function () {
        var db_id =$(this).prev('.ct-ue-input').attr('id').split("_");
        $.post("/experience/update", { field: "title_"+db_id[1], value: $(this).select2("val")}, function (data) {
            if(data.id && data.id>0){
                $( ".ct-ue-input" ).each(function(index) {
                    if($(this).attr('id').split("_")[0]!="city") {
                        id = $(this).attr('id').replace("_0", "_" + data.id);
                        $(this).attr('id', id);
                    }
                });
            }
            else {
                if(data.experience!="" && $('#years_0').length==0) {
                    $("#user_experience").prepend(data.experience);
                    $("#user_company").prepend(data.company);
                }
                if(data.action!='' && $('#'+data.action_for).length==0)
                    $( data.action ).insertAfter( $('#company_'+ data.action_for).next('.city-api') );
                if(data.exp_years) {
                    $('#sum_experience span').text(data.exp_years);
                }
            }
        }, "json");

    });

    //delete experience row
    $("section").on('click', '.ct-ue-delete', function () {
        id = $(this).attr('id');
        $.post("/experience/delete", {field: id }, function (data) {
            $('#rowid_' + id).next('.ct-ue-select').remove();
            $('#years_' + id).prev('.select2').remove();
            $('#years_' + id).remove();
            $('#company_' + id).next('.city-api').remove();
            $('#company_' + id).remove();
            $('#rowid_' + id).remove();
            $("#"+id).remove();
            if(data && data.experience!="" && $('#years_0').length==0){
                $("#user_experience").prepend(data.experience);
                $("#user_company").prepend(data.company);
            }
            if(data.exp_years) {
                $('#sum_experience span').text(data.exp_years);
            }
        }, "json");

    });
});

//load select2 options
$(".ct-ue-select").select2({
    ajax: {
        url: "/experience/getoptions",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term, // search term
                page: params.page
            };
        },
        processResults: function (data, page) {
            return {
                results: data
            };
        },
        cache: true
    },
    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
    minimumInputLength: 3,
});

//bind select2 on dynamic input
$('body #user_experience').on('DOMNodeInserted', 'select', function () {
    $(this).select2({
        ajax: {
            url: "/experience/getoptions",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, page) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,
    });
});

//city autocomplete-google places api callback event listener
$('body').on('DOMNodeInserted', 'input', function () {
    $('#user_company .city-api').each(function (i, v) {
        var inputID = $(this).attr('id');
        var placeSearch, autocomplete;
        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            country: 'long_name',
            postal_code: 'short_name'
        };
        autocomplete = new google.maps.places.Autocomplete(
            (document.getElementById(inputID)),
            {types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);

        function fillInAddress() {
            var place = autocomplete.getPlace();
            var params = {};
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    params[addressType] = val;
                }
            }
            var prevelement = $('#' + inputID).prev('.ct-ue-input').attr('id').split("_");

            params["id"] = "rowid_" + prevelement[1];
            params["searched"] = $('#' + inputID).val();
            $.post("/experience/savecity", params, function (data) {
                if (data && data.id > 0) {
                    $(".ct-ue-input").each(function (index) {
                        if ($(this).attr('id').split("_")[0] != "city") {
                            id = $(this).attr('id').replace("_0", "_" + data.id);
                            $(this).attr('id', id);
                        }
                    });
                }
                else {
                    if (data.experience != "" && $('#years_0').length == 0) {
                        $("#user_experience").prepend(data.experience);
                        $("#user_company").prepend(data.company);
                    }
                    if (data.action != '' && $('#' + data.action_for).length == 0)
                        $(data.action).insertAfter($('#company_' + data.action_for).next('.city-api'));
                    if(data.exp_years) {
                        $('#sum_experience span').text(data.exp_years);
                    }
                }
            }, "json");
        }
    });
});

//contact section city- google places autocomplete
$("#sec_contact").ready( function () {
    var inputID = "city";
    var placeSearch, autocomplete;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };
    autocomplete = new google.maps.places.Autocomplete(
        (document.getElementById(inputID)),
        {types: ['geocode']});
    autocomplete.addListener('place_changed', fillInAddress);

    function fillInAddress() {
        var place = autocomplete.getPlace();
        var params = {};
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                params[addressType] = val;
            }
        }
        params["searched"] = $('#' + inputID).val();
        $.post("/update/cityinput", params, function (data) {
           //void
        }, "json");
    }
});

//initialize popover
$("[data-toggle=popover]").popover({ trigger: "hover" });

//initialize inputmask - #CONTACT @birthdate
$('#sec_contact').ready(function(){
    $("#birthdate").inputmask();
});

//user #SEARCH city autocomplete-google places api callback event listener
$('body').on('DOMNodeInserted', 'input', function () {
    $('#user_city .srchcity-api').each(function (i, v) {
        var inputID = $(this).attr('id');
        var placeSearch, autocomplete;
        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            country: 'long_name',
            postal_code: 'short_name'
        };
        autocomplete = new google.maps.places.Autocomplete(
            (document.getElementById(inputID)),
            {types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);

        function fillInAddress() {
            var place = autocomplete.getPlace();
            var params = {};
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    params[addressType] = val;
                }
            }
            var prevelement = $('#' + inputID).prev('.ct-sc-input').attr('id').split("_");

            params["id"] = "srchrowid_" + prevelement[1];
            params["searched"] = $('#' + inputID).val();
            $.post("/usersearch/savecity", params, function (data) {
                if (data.mode=="add" && data.action_for > 0) {
                    $(".ct-sc-input").each(function (index) {
                        if ($(this).attr('id').split("_")[0] != "srchcity") {
                            id = $(this).attr('id').replace("_0", "_" + data.action_for);
                            $(this).attr('id', id);
                        }
                    });
                }
                if (data.searchcity != "" && $('#srchrowid_0').length == 0) {
                    $("#user_city").prepend(data.searchcity);
                    $('#user_city input:text').first().focus();
                }
                if (data.action != '' && $('#delcity_' + data.action_for).length == 0)
                    $(data.action).insertAfter($('#srchrowid_' + data.action_for).next('.srchcity-api'));

            }, "json");
        }
    });
});

//delete #SEARCH city rows
$("#user_city").on('click', '.ct-sc-delete', function () {
    var id = $(this).attr('id').split("_");
    $.post("/usersearch/delete", {field: id[1] }, function (data) {
        $('#srchrowid_' + id[1]).next('.srchcity-api').remove();
        $('#srchrowid_' + id[1]).remove();
        $('#delcity_' + id[1]).remove();
        if(data && data.searchcity!="" && $('#srchrowid_0').length==0){
            $("#user_city").prepend(data.searchcity);
            if(data.searchcity!=undefined && data.searchcity!="")
                $('#user_city input:text').first().focus();
        }
    }, "json");
});

//User Languages section - START
var colors     = ['rgba(81, 159, 211, 1)','rgba(101, 198, 151, 1)','rgba(255, 185, 16, 1)','rgba(238, 52, 100, 1)'];
var langDBData = '';

// language section - modal
$( "#hidedefaultlanguage, #language_handler" ).click(function() {
    var dialog = $('#languagemodal .modal-dialog');
    dialog.html('<div class="modal-content"><div class="modal-body"><div class="user-language-data"></div><div class="appendRow"></div><select class="form-control input-sm addMore"><option selected="selected">Neue Sprache hinzufügen</option></select></div><div class="modal-footer"><button type="button" class="btn btn-success divclose" data-dismiss="modal">Speichern und schließen</button></div></div>');
    var modalBody = dialog.find('.user-language-data');
    // process langDBData - make the row, set the ranking and append to modal
    $.each(langDBData.selectLanguageRankingTagId, function (i, item) {
        var newRow = $('<div class="row" id="del-userlanguage_' + item.id + '" style="margin-bottom: 10px;"><div class="col-md-9"><div class="caption" style="background-color: lightgrey;"><div class="label"><span class="glyphicon glyphicon-remove removefetchedData" data-id="' + item.id + '"></span>' + item.title_de + '</div></div></div><div class="col-md-3"><select class="form-control input-sm colorLoadData" id="' + item.id + '" data-item="' + item.id + '"><option value="1">Grundkenntnisse</option><option value="2">Gut</option><option value="3">Fließend</option><option value="4">Muttersprache</option></select></div></div>');
        newRow.find('select').val(item.ranking);
        modalBody.append(newRow);
    });
    //set and update colours of loaded data
    $('.colorLoadData').change(function(e){
        $(e.target.parentNode).parent('div.row').find('.caption').css({"background-color":colors[e.target.selectedIndex],"width" :(parseInt(e.target.options[e.target.selectedIndex].value) * 25) +"%"});
    });
    $('select.colorLoadData').each(function(index, element) {
            $(element.parentNode).parent('div.row').find('.caption').css({"background-color":colors[element.selectedIndex],"width" :(parseInt(element.options[element.selectedIndex].value) * 25) +"%"});
    });
    $("#hidedefaultlanguage" ).hide();
    $("#languagemodal").show();

    //bind select-2 control
    $('.modal-dialog .addMore').select2({
        ajax: {
            url: "/tag/language",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, page) {
                return {
                    results: data
                };
            },
            cache: false
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1,
    });
});

// add a new language row
$("#languagemodal .modal-dialog").on('change', ".addMore", function(e){
    var dialog = $('#languagemodal .modal-dialog');
    var appendLangRow = dialog.find('.appendRow');
    language       = $( this ).select2('data')[0].text;
    languageID     = $( this ).select2('val');
    var newlangRow = $('<div class="row" id="field-count_' + languageID + '" style="margin-bottom: 10px;"><div class="col-md-9"><div class="caption" style="background-color: lightgrey;"><div class="label"><span class="glyphicon glyphicon-remove removeRow"></span>' + language + '</div></div></div><div class="col-md-3"><select class="form-control input-sm colorLoadGeneratedData"  data-langid="' + languageID + '" data-langname="' + language + '"><option value="1">Grundkenntnisse</option><option value="2">Gut</option><option value="3">Fließend</option><option value="4">Muttersprache</option></select></div></div>       ');
    appendLangRow.append(newlangRow);
    ranking    = newlangRow.find('select').val();
    $.post("/language/insert", {rate: ranking, userLangLangID: languageID, langname: language}, function(html){
        for (var i = 0; i < html.userLangNameRank.length; i++) {
            newlangRow.find('select').data('item', html.userLangNameRank[i].id);
            rankingColor = html.userLangNameRank[i].ranking * 25;
            var color = getRankColor(html.userLangNameRank[i].ranking);
            newlangRow.find('.caption').css({"background-color":colors[0],"width" :(parseInt(1) * 25) +"%"});
            $( ".loadDefaultUserLanguage" ).append('<div class="col-md-8" id="updated-language_' + html.userLangNameRank[i].id + '" style="background-color: lightgrey; background-repeat: repeat; padding: 0px; margin-top: 5px;"><div style="color: #FFF; background-color:'+color+'; width:'+rankingColor+'%; padding: 8px; background-repeat: repeat;" class="text-left col-md-6">'+html.userLangNameRank[i].title_de+'</div></div>');
        }
    });
});

//update language
$("#languagemodal .modal-dialog").on("change",'.colorLoadGeneratedData, .colorLoadData', function(e){
    $(e.target.parentNode).parent('div.row').find('.caption').css({"background-color":colors[e.target.selectedIndex],"width" :(parseInt(e.target.options[e.target.selectedIndex].value) * 25) +"%"});
    ranking    = $(this).val();
    userLangID = $(this).data('item');
    $.post("/language/update", {rate: ranking, rateUserLangID: userLangID}, function(html){
        for (var i = 0; i < html.updateUserLangNameRank.length; i++) {
            rankingColor = html.updateUserLangNameRank[i].ranking * 25;
            var color = getRankColor(html.updateUserLangNameRank[i].ranking);
            $("#updated-language_"+ html.updateUserLangNameRank[i].id).html('<div style="color: #FFF; background-color:'+color+'; width:'+rankingColor+'%; padding: 8px; background-repeat: repeat;" class="text-left col-md-6">'+html.updateUserLangNameRank[i].title_de+'</div>');
        }
    });
});

//remove rank and language of users
$('#languagemodal .modal-dialog').on('click','.removefetchedData',function(e){
    e.preventDefault();
    userLangDelID = $(this).data('id');
    $.post("/language/delete", {delUserLangID: userLangDelID}, function(html){
        $("#del-userlanguage_" + html.successDelUserLangID).hide();
        $(".del-userlanguage_" + html.successDelUserLangID).hide();
        $("#updated-language_" + html.successDelUserLangID).remove();
    });
});

//remove newly added language row
$('#languagemodal .modal-dialog').on('click', '.removeRow', function(e) {
    e.preventDefault();
    userLangDelID = $(this).closest('.row').find('select').data('item');
    $(this).closest('.row').remove();
    $.post("/language/delete", {delUserLangID: userLangDelID}, function(html){
        $("#updated-language_" + html.successDelUserLangID).remove();
    });
    return false;
});

//Refresh all changes to DOM and close modal
$("#languagemodal" ).on('click', '.divclose', function(e){
    e.preventDefault();
    $('div[id^="updated-language_"]').each( function() {
        $(this).remove();
    });
    loadLanguageData();
    setTimeout( function() {
        $('#languagemodal .modal-dialog').html('');
        $('#languagemodal').data('modal', null);
        $('#languagemodal').hide();
        $('#hidedefaultlanguage').show();
    },500);
});

// get from db add process on dom load */
function loadLanguageData() {
    $.get("/language/ranking/get", function (responseDB) {
        langDBData = responseDB;
        $.each(responseDB.selectLanguageRankingTagId, function (i, item) {
            rankingColor = item.ranking * 25;
            var color = getRankColor(item.ranking);
            $(".loadDefaultUserLanguage").append('<div class="col-md-8 del-userlanguage_' + item.id + '" id="updated-language_' + item.id + '" style="background-color: lightgrey; background-repeat: repeat; padding: 0px; margin-top: 5px;"><div style="color: #FFF; background-color:' + color + '; width:' + rankingColor + '%; padding: 8px; background-repeat: repeat;" class="text-left col-md-6">' + item.title_de + '</div></div>');

        });
    });
}

//trigger loadLanguageData
$('#hidedefaultlanguage').ready( function(){
    loadLanguageData();
});

//Function returns ranking color code
function getRankColor(ranking)
{
    var color = colors[0];
    if(ranking > 0) {
        color = colors[eval(ranking-1)];
    }
    return color;
}
//User Languages section - END

//User Profession new section - START
var profDBData = '';
$('#profession_handler').click(function() {
    var dialog = $('#profession_modal');
    dialog.html('');
    dialog.html('<div class="modal-content"><div class="modal-body">' +
        '<div class="user-profession-data"></div><div class="innerRow"></div>' +
        '<br><form id="add_profession"><div class="row">' +
        '<div class="col-md-8"><select name="graduation_id" class="form-control input-sm addGraduation"><option selected="selected" value="">Abschluss</option></select></div>' +
        '<div class="col-md-4"><select name="grade" class="form-control input-sm gradeSelector"><option selected="selected" value="">Note</option></select></div>' +
        '<div class="col-md-8" style="margin-top: 5px;"><select name="experience_id" class="form-control input-sm subjectSelector"><option selected="selected" value="">Fach</option></select></div>' +
        '<div class="col-md-4" style="margin-top: 5px;"><button type="button" class="btn btn-block btn-primary processRequest disabled" title="Choose a graduation" disabled>Hinzufügen</button></div>' +
        '</div></form>' +
        '</div>' +
        '<div class="modal-footer"><button type="button" class="btn btn-success hideModal" data-dismiss="modal">Speichern und schließen</button></div>' +
        '</div>');
    var modalBody = dialog.find('.user-profession-data');
    // process profDBData - make the row, set the ranking and append to modal
    $.each(profDBData.userProfessions, function (i, item) {
        var subject = (item.subject!=null)?item.subject:"";
        var grade   = (item.grade!=0.0)?' - Note: '+ item.grade:"<br>";
        var dataRow = $('<div class="profLabel tag-individual" style="position: relative;"><strong>' + item.graduation + '</strong>' + grade + '<br>' + subject + '<span id="rmrow_' + item.id + '" class="glyphicon glyphicon-remove removeProfRow" style="position: absolute; right: 0; padding: 7px 15px;"></span></div>');
        modalBody.append(dataRow);
    });
    $("#profession_handler").hide();
    $("#profession_container" ).hide();
    $("#user_professions").show();

    //Graduation selector select-2
    $('#profession_modal .addGraduation').select2({
        ajax: {
            url: "/profession/graduation",
            dataType: 'json',
            delay: 100,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, page) {
                return {
                    results: data
                };
            },
            cache: false
        },
        escapeMarkup: function (markup) {
            return markup;
        } // let our custom formatter work
    });
    // subjectSelector - select 2
    $('#profession_modal .subjectSelector').select2({
        ajax: {
            url: "/profession/experience",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, page) {
                return {
                    results: data
                };
            },
            cache: false
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
    });
    //set grade options
    $('#profession_modal .gradeSelector').populate();
});

// add a new profession row
$('#profession_modal').on('click', ".processRequest", function(e){
    var dialog = $('#profession_modal');
    var appendProfRow = dialog.find('.innerRow');
    graduation       = $('.addGraduation').select2('data')[0].text;
    experience       = ($('.subjectSelector').select2('val')!='')? $( '.subjectSelector' ).select2('data')[0].text:"";
    grade            = ($('.gradeSelector').val()!='')? ' - Note: ' +$( '.gradeSelector :selected' ).text():"<br>";
    var newprofRow = $('<div class="profLabel tag-individual" style="position: relative;"><strong>' + graduation + '</strong> ' + grade + '<br>' + experience + '<span class="glyphicon glyphicon-remove removeProfRow" style="position: absolute; right: 0; padding: 7px 15px;"></span></div>');
    var data = $('#add_profession').serialize();
    $.post("/profession/insert", data)
        .done(function (responseData) {
            if(responseData.professionID>0) {
                appendProfRow.append(newprofRow);
                newprofRow.find('span').attr('id', 'rmrow_' + responseData.professionID);
                $(".addGraduation").select2("val", "");
                $(".subjectSelector").select2("val", "");
                $('#add_profession')[0].reset();
                $('.processRequest').attr('disabled', true).attr('title','Choose a graduation').addClass('disabled');
            }
            else {
                alert('Please choose a Graduation');
            }
        })
        .fail(function () {
            alert("Please choose a Graduation");
        });
});

//remove Profession
$('#profession_modal').on('click', '.removeProfRow', function(e) {
    e.preventDefault();
    professionrow = $(this).attr('id').split("_");
    $.post("/profession/delete", { professionID: professionrow[1]}, function(html){
        if(html.professionID>0)
            $('#rmrow_'+html.professionID).closest('.profLabel').remove();
    });
});

//Load all changes and close profession modal
$("#profession_modal" ).on('click', '.hideModal', function(e){
    e.preventDefault();
    $('div[id^="user-profession_"]').each( function() {
        $(this).remove();
    });
    loadProfessionData();
    setTimeout( function() {
        $("#user_professions").hide();
        $("#profession_container").show();
        $("#profession_handler").show();
    },500);
});

// get from db add process on dom load */
function loadProfessionData() {
    $.get("/profession/getlist", function (responseDB) {
        profDBData = responseDB;
        $.each(responseDB.userProfessions, function (i, item) {
            var subject = (item.subject!=null)?item.subject:"";
            var grade = (item.grade!=0.0)?' - Note: '+item.grade:"<br>";
            $("#profession_container").append('' +
                '<div id="user-profession_' + item.id + '" class="tag-individual">' +
                '<strong>' + item.graduation + '</strong> ' + grade + '<br>' +
                '' + subject + '' +
                '</div>');
        });
    });
}

//Add button state on Graduation select-2 change
$("#profession_modal").on("change",'.addGraduation', function(e) {
   if($(this).select2('val')>0) {
       $('.processRequest').attr('disabled', false).attr('title','Add graduation').removeClass('disabled');
   }
});

//loadProfessionData on ready state
$('#profession_container').ready( function(){
    loadProfessionData();
    $("#profession_handler" ).show();
});

// Grade Options
$.fn.populate = function() {
    for ( var i=1.0; i<4.1; i+=0.1 ) {
        var opt = i.toFixed(1);
        $(this).append('<option value="'+opt+'">'+ opt +'</option>');
    }
};


//User Profession new section - END

$(".pdfupload").filestyle({buttonText: " PDF hochladen", badge: false, input: false});

//Delete Ability Test
$( "#graph_function" ).on('click','#del_abilitytest',function() {
    $.post("/abilitytest/delete", {}, function(){
        $('#graph .btn-group').show();
        setGraphWidth();
    });
});

//Manual graph ranking feature
$("#graph").find("button").click(function () {
    var slider     = $(this).parents('.bar').find('.slider');
    var width      = slider.attr('style').match(/\d+/);
    var clicked_on = $(this).find('span').attr('class').split("arrow-");
    if(clicked_on[1]=="right")
    {
        width = parseInt(width)-parseInt(25);
        if(width==0) {
            $(this).attr('disabled', true);
        }
        $(this).prev('button').attr('disabled', false);
        slider.css('width',width+'%');
    }
    else {
        width = parseInt(width) + parseInt(25);
        if (width == 100) {
            $(this).attr('disabled', true);
        }
        $(this).next('button').attr('disabled', false);
        slider.css('width',width+'%');
    }
    var graph1 = eval($("#bar1_slider").attr('style').match(/\d+/)) / 25;
    var graph2 = eval($("#bar2_slider").attr('style').match(/\d+/)) / 25;
    var graph3 = eval($("#bar3_slider").attr('style').match(/\d+/)) / 25;
    var graph4 = eval($("#bar4_slider").attr('style').match(/\d+/)) / 25;
    $.post("/graph", {graph1: graph1, graph2: graph2, graph3: graph3, graph4: graph4}, function (response) {
        });
});

$('#graph').ready( function() {
    $('#graph .btn-group').hide();
    if($('#abilitytest').length)
    {
        $('#graph .slider').each( function(){
            var width      = $(this).attr('style').match(/\d+/);
            if(width==100){
                $(this).parent().find('.btn-group button:first').attr('disabled',true);
            }
            else if(width==0){
                $(this).parent().find('.btn-group button:last').attr('disabled',true);
            }
            else{
                $(this).parent().find('.btn-group button:first').attr('disabled',false);
                $(this).parent().find('.btn-group button:last').attr('disabled',false);
            }
        });
       $('#graph .btn-group').show();
    }
});

// set graph width on delete of Ability test results
function setGraphWidth() {
    $.get("/profile/graph", function (response) {
        $.each(response, function (i, item) {
            $('#'+i).find('.slider').width(item + '%');
            if(item==100) {
                $('#'+i).find('.btn-group button:first').attr('disabled',true);
            }
            else if(item==0){
                $('#'+i).find('.btn-group button:last').attr('disabled',true);
            }
            else{
                $('#'+i).find('.btn-group button:first').attr('disabled',false);
                $('#'+i).find('.btn-group button:last').attr('disabled',false);
            }
        });
        $('#graph_function').html(response.buttonhtml);
    });
}

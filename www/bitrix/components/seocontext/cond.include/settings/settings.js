//todo: add namespace

var seocontext = seocontext || {};
if (typeof seocontext.condinclude !== 'undefined')
    console.warn("Namespace condinclude is already exists.");
else
    seocontext.condinclude = {};

(function(){

    var inputBasePath;
    const contentManagerUrl = '/bitrix/components/seocontext/cond.include/settings/content.php';
    var locationInParams;
    var selectedLocationCode;

    this.onSettingsCreate = function(arParams)
    {
        //console.log(arParams.getElements());
        inputBasePath = arParams.getElements()["CONTENT_DIR"];
        var container = $(arParams.oCont);
        var that = this;
        // todo: replace with relative path
        $.get('/bitrix/components/seocontext/cond.include/settings/settings.php').done(function(html){
            //console.log(html);
            var $html = $(html);
            $html.find('#save-btn').on('click',function(){
                //var hidden = $(this).parent().find('input[type="hidden"]');
                //hidden.val(textarea.html());
                //console.log(hidden);
                that.showLoading();
                var content = CKEDITOR.instances['seocontext_content_editor'].getData();
                that.saveLocationSpecificContent(locationInParams,content).done(function(){
                    that.hideLoading();
                });
                return false;
            });

            $html.find('#del-loc').on('click', function(){
                var selectedOptionCode = $("#select-location option:selected").val();
                deleteLocationFromTable(selectedOptionCode);
                return false;
            });

            $html.find('#add-loc').on('click', function(){
                addLocationToTable(selectedLocationCode);
                console.log(selectedLocationCode);
                $html.find('input').val("");
                return false;
            });

            $html.find('select').on('change',function(){
                var locCode = $(this).val();
                that.locationChanged(locCode);
                var index= document.getElementById("select-location").selectedIndex;
                if(index != 0)
                    $html.find('button#del-loc').prop( "disabled", false );
                else
                    $html.find('button#del-loc').prop( "disabled", true );
            });

            $html.find('select').on('onblur',function(){
                $html.find('button#del-loc').prop( "disabled", true );
            });

            $html.find('button#add-loc').prop( "disabled", true );
            $html.find('button#del-loc').prop( "disabled", true );

            // REMOVED FOR NOW
            // location.js is not loaded when cond.include component is just added
            // in page editing form so we must check it
            //if (typeof seocontext.location !== "undefined")
            //{
            //    var currentLocation = seocontext.location.get();
            //    if (currentLocation) {
            //        var name = currentLocation.name;
            //        $html.find('input').val(name);
            //        that.locationChanged(currentLocation.code);
            //    }
            //}

            that.setAutocomplete($html.find('input'));

            container.append($html);
            that.loadScript('http://cdn.ckeditor.com/4.5.7/standard/ckeditor.js').done(function(){
                CKEDITOR.replace('seocontext_content_editor');
            });
        });

        return;

        //$.cachedScript('http://cdn.tinymce.com/4/tinymce.min.js').done(function(){
        //    tinymce.init({
        //        selector: 'textarea'
        //    });
        //    console.log("done");
        //});

    }

    function addLocationToTable(code)
    {
        $.post(contentManagerUrl, { Code : code, action: "addloc"},
            function(data)
            {
                $result = $.parseJSON(data);
                console.log($result['name'] + " = " + $result['code']);

                $('#select-location')
                    .append($('<option>', { value : $result['code'] })
                        .text($result['name']));
                $('button#add-loc').prop( "disabled", true );
            });
    }

    function deleteLocationFromTable(code)
    {
        if(code == 'default')
            return false;
        else
        {
            $.post(contentManagerUrl, {Code: code, action: "delloc"},
                function (data) {
                    $("#select-location option[value='"+code+"']").remove();
                });
        }
    }

    this.setAutocomplete = function (input) {
        // setting autocomplete
        var that = this;
        var options = {
            // todo: make relative or automatic
            serviceUrl: '/bitrix/components/seocontext/cond.include/settings/search-ajax.php',
            dataType: 'json',
            showNoSuggestionNotice: true,
            minChars: 2,
            onSelect: function(suggestion){
                console.log(suggestion);
                that.locationChanged(suggestion.data);
                selectedLocationCode = suggestion.data;
                $('button#add-loc').prop( "disabled", false );
                //locationManager.setCurrentLocation(suggestion.data, suggestion.value);
            }
        };

        // check if devbridge is loaded
        if (typeof $.fn.devbridgeAutocomplete === "undefined")
        {
            this.loadScript('/bitrix/js/seocontext.locations/devbridge/jquery.autocomplete.min.js')
                .done(function(){
                    $(input).devbridgeAutocomplete(options);
                });
        }
        else
        {
            $(input).devbridgeAutocomplete(options);
        }
    };

    this.locationChanged = function(locCode)
    {
        this.showLoading();
        console.log(locCode);
        locationInParams = locCode;
        var that = this;
        this.getLocationSpecificContent(locCode).done(function (html) {
            CKEDITOR.instances['seocontext_content_editor'].setData(html);
            that.hideLoading();
        });
    }

    this.showLoading = function()
    {

    }

    this.hideLoading = function()
    {

    }

    this.getLocationSpecificContent = function(locationCode)
    {
        var dir = $(inputBasePath).val();
        return $.get(contentManagerUrl,{code: locationCode, dir: dir});
    }

    this.saveLocationSpecificContent = function(locationCode, content)
    {
        var dir = $(inputBasePath).val();
        return $.post(contentManagerUrl, {code: locationCode, content: content, action: 'save', dir: dir});
    }


    this.loadScript = function( url, options ) {
        // Allow user to set any option except for dataType, cache, and url
        options = $.extend( options || {}, {
            dataType: "script",
            cache: true,
            url: url
        });

        // Use $.ajax() since it is more flexible than $.getScript
        // Return the jqXHR object so we can chain callbacks
        return jQuery.ajax( options );
    };

}).apply(seocontext.condinclude);

function onCondIncludeSettingsCreate(arParams)
{
    // check jquery
    if (!window.jQuery)
    {
        var jqueryScript = document.createElement('script');
        jqueryScript.type = 'text/javascript';
        // Path to jquery.js file, eg. Google hosted version
        jqueryScript.src = '//code.jquery.com/jquery-1.12.0.min.js';
        jqueryScript.onload = function(s){
            console.log("jquery loaded");
            seocontext.condinclude.onSettingsCreate(arParams);
        }
        document.getElementsByTagName('head')[0].appendChild(jqueryScript);
    }
    else
    {
        seocontext.condinclude.onSettingsCreate(arParams);
    }
}
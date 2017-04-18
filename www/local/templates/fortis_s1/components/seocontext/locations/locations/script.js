$(function () {
    $.noConflict();
    $(document).on('location:change', function () {
        // check if need to reload
        if ($('input#seocontext_locations_reload').length > 0)
            document.location.reload(true);

        var newLocation = seocontext.location.get();
        console.log('location was changed: ' + newLocation.name);
        if (newLocation) {
            $(".seocontext-selected-location").text(newLocation.name);
        }
        displayDetectingBlock(false);
    });

    // check cache
    var currLocation = seocontext.location.get();
    if (currLocation) {
        $(".seocontext-selected-location").text(currLocation.name);
        displayDetectingBlock(false);
    }
    else {
        // detect current position
        ymaps.ready(init);
    }

    // setting autocomplete
    var options = {
        serviceUrl: '/bitrix/components/seocontext/locations/templates/.default/search-ajax.php',
        dataType: 'json',
        showNoSuggestionNotice: true,
        minChars: 2,
        onSelect: function (suggestion) {
            console.log(suggestion);
            var name = suggestion.value.split(',')[0];
            currLocation = {'code': suggestion.data, 'name': name};
        },
    };
    $('input[name="location"]').devbridgeAutocomplete(options);

    // setting popup
    $('.seocontext-locations-show').magnificPopup({
        type: 'inline',
        midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
        //closeBtnInside: true
    });

    $(".seocontext-locations button").on('click', function () {
        $.magnificPopup.close();
        if (currLocation)
            seocontext.location.set(currLocation.code, currLocation.name);
    });

    $('ul.selected-locations li').on('click', function () {
        $li = $(this);
        console.log('setting location');
        var code = $li.attr('data-code');
        var name = $li.text().trim();
        //location = {'code': code, 'name': name};
        seocontext.location.set(code, name);
        $.magnificPopup.close();
    });

    function init()
    {
        var addressLine;
        var country;
        var administrativeArea;
        var subAdministrativeArea;
        var locality;
        var CityAndCode;

        var geolocation = ymaps.geolocation;
        // position by ip
        geolocation.get({
            provider: 'yandex'
        }).then(function (result) {
            var res = result.geoObjects.get(0).properties._data.metaDataProperty.GeocoderMetaData.AddressDetails.Country;
            addressLine = res['AddressLine'];
            country = res['CountryName'];
            administrativeArea = res['AdministrativeArea']['AdministrativeAreaName'];
            subAdministrativeArea = res['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName'];
            locality = res['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName'];

            //console.log(result.geoObjects.get(0).properties._data.metaDataProperty);
            getBitrixLocationCodeAndName(locality, subAdministrativeArea, administrativeArea);
        });
    }

    /**
     *
     * @param show boolean
     */
    function displayDetectingBlock(show)
    {
        if (show)
        {
            $(".seocontext-detecting-location").show();
            $(".seocontext-selected-location").hide();
        }
        else
        {
            $(".seocontext-detecting-location").hide();
            $(".seocontext-selected-location").show();
        }
    }

    var currPositionName;
    function getBitrixLocationCodeAndName(locality, subAdministrativeArea, administrativeArea)
    {
        $.post("/bitrix/components/seocontext/locations/templates/.default/getLocation.php", { Locality: locality, SubAdministrativeArea: subAdministrativeArea , AdministrativeArea: administrativeArea},
            function(data){})
            .done(function(data) {
                var post_res = $.parseJSON(data);
                console.log(post_res[1]);
                currPositionName = post_res[1];
                $(".seocontext-detected-location").text(currPositionName);
                displayDetectingBlock(true);

                $(".seocontext-detected-location-yes").on("click", function (e)
                {
                    seocontext.location.set(post_res[0], post_res[1]);
                    displayDetectingBlock(false);
                    e.preventDefault();
                });
            });
    }
});


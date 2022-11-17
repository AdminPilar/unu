<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="utf-8" />

    <!-- Reference to the Bing Maps SDK -->
    <script type='text/javascript'
            src='http://www.bing.com/api/maps/mapcontrol?callback=GetMap' async defer></script>

    <script type='text/javascript'>

    var bingMap = null;

    function GetMap()
    {
        var zom = 15;
        var lat = -15.864245;//-15.827854;
        var lon = -69.997457;//-70.016383;

        var map = new Microsoft.Maps.Map('#myMap', {
            credentials: 'ympb4OxY1i1dTi3tTyt9~lnq9lZcquTBGggiCegdYIA~Atf1Qhgkm8A21m5_6q4PcGMDvIbNJUE8Guhy66-KnaOO6gBg005OqIffGdZJ13oF',
            center: new Microsoft.Maps.Location(lat, lon),
            mapTypeId: Microsoft.Maps.MapTypeId.streetside,
            zoom: zom
        });

        bingMap = map;


        Microsoft.Maps.loadModule("Microsoft.Maps.SpatialMath", function () {
            //Request the user's location
            //navigator.geolocation.getCurrentPosition(function (position) {
                var loc = new Microsoft.Maps.Location( lat, lon ); //position.coords.latitude, position.coords.longitude);

                //Add a pushpin at the user's location.
                var pin = new Microsoft.Maps.Pushpin(loc);
                map.entities.push(pin);


                Microsoft.Maps.Events.addHandler(pin, 'click', displayEventInfo);
                bingMap.entities.push(pin);

                //Center the map on the user's location.
                map.setView({ center: loc, zoom: zom });
            //});


        });


    }


    function displayEventInfo(e)
    {
        console.log ( "click" );

        if (e.targetType == "pushpin") {
            var pix = bingMap.tryLocationToPixel(e.target.getLocation(), Microsoft.Maps.PixelReference.control);
            console.log( pix );

            //$("#infoboxTitle").html(e.target.title);
            //$("#infoboxDescription").html(e.target.description);

            /*
            var infobox = $("#infoBox");
            infobox.css({
                "top": (pix.y - 60) + "px",
                "left": (pix.x + 5) + "px",
                "visibility": "visible"
            });
            */

            //$("#mapDiv").append(infobox);
            //alert( infobox );
        }
    }
    </script>
</head>
<body>
    <div id="myMap" style="position:relative;width:100%;height:80%;"></div>
    <div id="infoboxTitle">
    </div>
    <div id="infoboxDescription">
    </div>
    <div id="infoBox">
    </div>
    <div id="mapDiv">
    </div>
</body>
</html>
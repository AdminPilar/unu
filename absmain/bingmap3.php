<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="utf-8" />
    <script type='text/javascript'>
    var map;

    function GetMap() {
        map = new Microsoft.Maps.Map('#myMap', {
            credentials: 'ympb4OxY1i1dTi3tTyt9~lnq9lZcquTBGggiCegdYIA~Atf1Qhgkm8A21m5_6q4PcGMDvIbNJUE8Guhy66-KnaOO6gBg005OqIffGdZJ13oF',
            mapTypeId: Microsoft.Maps.MapTypeId.streetside,
            center: new Microsoft.Maps.Location(-15.833115, -70.019956),
            zoom: 50
        });

        Microsoft.Maps.loadModule("Microsoft.Maps.SpatialMath", function () {
            //Request the user's location
            //navigator.geolocation.getCurrentPosition(function (position) {
                var loc = new Microsoft.Maps.Location( -15.833115, -70.019956 ); //position.coords.latitude, position.coords.longitude);

                //Create an accuracy circle
                //var path = Microsoft.Maps.SpatialMath.getRegularPolygon(loc, position.coords.accuracy, 36,  Microsoft.Maps.SpatialMath.Meters);
                //var poly = new Microsoft.Maps.Polygon(path);
                //map.entities.push(poly);

                //Add a pushpin at the user's location.
                var pin = new Microsoft.Maps.Pushpin(loc);
                map.entities.push(pin);

                //Center the map on the user's location.
                map.setView({ center: loc, zoom: 80 });
            //});
        });

        //Add view change events to the map.
        Microsoft.Maps.Events.addHandler(map, 'viewchangestart', function () { highlight('mapViewChangeStart'); });
        Microsoft.Maps.Events.addHandler(map, 'viewchange', function () { highlight('mapViewChange'); });
        Microsoft.Maps.Events.addHandler(map, 'viewchangeend', function () { highlight('mapViewChangEnd'); });

        //Add mouse events to the map.
        Microsoft.Maps.Events.addHandler(map, 'click', function () { highlight('mapClick'); });
        Microsoft.Maps.Events.addHandler(map, 'dblclick', function () { highlight('mapDblClick'); });
        Microsoft.Maps.Events.addHandler(map, 'rightclick', function () { highlight('mapRightClick'); });
        Microsoft.Maps.Events.addHandler(map, 'mousedown', function () { highlight('mapMousedown'); });
        Microsoft.Maps.Events.addHandler(map, 'mouseout', function () { highlight('mapMouseout'); });
        Microsoft.Maps.Events.addHandler(map, 'mouseover', function () { highlight('mapMouseover'); });
        Microsoft.Maps.Events.addHandler(map, 'mouseup', function () { highlight('mapMouseup'); });
        Microsoft.Maps.Events.addHandler(map, 'mousewheel', function () { highlight('mapMousewheel'); });

        //Add addition map event handlers
        Microsoft.Maps.Events.addHandler(map, 'maptypechanged', function () { highlight('maptypechanged'); });
    }

    function highlight(id) {
        //Highlight the div to indicate that the event has fired.
        document.getElementById(id).style.background = 'LightGreen';

        //Remove the highlighting after a second.
        setTimeout(function () { document.getElementById(id).style.background = 'white'; }, 1000);
    }
    </script>
    <script type='text/javascript' src='http://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=' async defer></script>
</head>
<body>
    <div id="myMap" style="position:relative;width:800px;height:600px;"></div>

    <div id="mapViewChangeStart">viewchangestart</div>
    <div id="mapViewChange">viewchange</div>
    <div id="mapViewChangEnd">viewchangeend</div>

    <div id="mapClick">click</div>
    <div id="mapDblClick">dblclick</div>
    <div id="mapRightClick">rightclick</div>
    <div id="mapMousedown">mousedown</div>
    <div id="mapMouseout">mouseout</div>
    <div id="mapMouseover">mouseover</div>
    <div id="mapMouseup">mouseup</div>
    <div id="mapMousewheel">mousewheel</div>

    <div id="maptypechanged">maptypechanged</div>
</body>
</html>
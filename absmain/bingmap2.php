<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="utf-8" />
    <script type='text/javascript'>
    var BingMapsKey = 'ympb4OxY1i1dTi3tTyt9~lnq9lZcquTBGggiCegdYIA~Atf1Qhgkm8A21m5_6q4PcGMDvIbNJUE8Guhy66-KnaOO6gBg005OqIffGdZJ13oF';

    function geocode() {
        var query = document.getElementById('input').value;

        var geocodeRequest = "http://dev.virtualearth.net/REST/v1/Locations?query=" + encodeURIComponent(query) + "&jsonp=GeocodeCallback&key=" + BingMapsKey;

        CallRestService(geocodeRequest, GeocodeCallback);
    }

    function GeocodeCallback(response) {
        var output = document.getElementById('output');

        if (response &&
            response.resourceSets &&
            response.resourceSets.length > 0 &&
            response.resourceSets[0].resources) {

            var results = response.resourceSets[0].resources;

            var html = ['<table><tr><td>Name</td><td>Latitude</td><td>Longitude</td></tr>'];

            for (var i = 0; i < results.length; i++) {
                html.push('<tr><td>', results[i].name, '</td><td>', results[i].point.coordinates[0], '</td><td>', results[i].point.coordinates[1], '</td></tr>');
            }

            html.push('</table>');

            output.innerHTML = html.join('');
        } else {
            output.innerHTML = "No results found.";
        }
    }

    function CallRestService(request) {
        var script = document.createElement("script");
        script.setAttribute("type", "text/javascript");
        script.setAttribute("src", request);
        document.body.appendChild(script);
    }
    </script>
</head>
<body>
    <input type="text" id="input" value="New York" />
    <input type="button" onClick="geocode()" value="Search" /><br />
    <div id="output"></div>
</body>
</html>
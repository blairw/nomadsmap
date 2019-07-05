var globalMap;
var globalAnimationToggle = true;
var globalDarkModeToggle = false;

var attributionPrefix = '<a href="https://medium.com/@ryancatalani/creating-consistently-curved-lines-on-leaflet-b59bc03fa9dc">Animated Curves</a>';
attributionPrefix += ' | <a href="https://p.yusukekamiyamane.com/">Fugue</a>'
attributionPrefix += " | ";

var CartoDB_Positron = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: attributionPrefix + '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
    subdomains: 'abcd',
    maxZoom: 19
});
var CartoDB_DarkMatter = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: attributionPrefix + '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
    subdomains: 'abcd',
    maxZoom: 19
});

function bodyDidLoad() {
    drawMap(globalAnimationToggle);
}

function redrawMapToggleAnimation() {
    globalMap.remove();
    globalAnimationToggle = !globalAnimationToggle;
    drawMap(globalAnimationToggle);
}

function redrawMapToggleDarkMode() {
    globalDarkModeToggle = !globalDarkModeToggle;

    if (globalDarkModeToggle) {
        CartoDB_Positron.removeFrom(globalMap);
        CartoDB_DarkMatter.addTo(globalMap);
    } else {
        CartoDB_DarkMatter.removeFrom(globalMap);
        CartoDB_Positron.addTo(globalMap);
    }
}

function drawMap(withAnimations) {
    globalMap = L.map('mapid', {zoomSnap: 0.25}).fitBounds([[65,-130],[-40,179]]);

    if (globalDarkModeToggle) {
        CartoDB_DarkMatter.addTo(globalMap);
    } else {
        CartoDB_Positron.addTo(globalMap);
    }
    
    $.getJSON(API_ROOT + "getTrips.json?revision=8", function(data){
        for (var i = 0; i < data.length; i++) {
            var thisLoc = data[i];
            var curvedPathLatLongs = calculateCurvedEarthControlPoint(
                [parseFloat(thisLoc.x_lat), parseFloat(thisLoc.x_long)], 
                [parseFloat(thisLoc.y_lat), parseFloat(thisLoc.y_long)]
            );

            var pathOptions = {
                color: (thisLoc.counter < 20 ? "rgb(20,120,230)" : "rgb(255,0,100)"),
                weight: (thisLoc.counter < 20 ? 1 : 3),
                opacity: (thisLoc.counter < 20 ? 0.5 : 0.75)
            };

            // source: https://medium.com/@ryancatalani/creating-consistently-curved-lines-on-leaflet-b59bc03fa9dc
            if (withAnimations && typeof document.getElementById('mapid').animate === "function") { 
                var durationBase = 500;
                var logRvalue = Math.log(curvedPathLatLongs.rValue);

                var duration = Math.sqrt(logRvalue < 0 ? 1 : logRvalue) * durationBase;
                // Scales the animation duration so that it's related to the line length
                // (but such that the longest and shortest lines' durations are not too different).
                    // You may want to use a different scaling factor.
                pathOptions.animate = {
                    duration: duration,
                    iterations: Infinity,
                    easing: 'ease-in-out',
                    direction: 'alternate',
                    delay: Math.random() * 10 * durationBase
                }
            }

            var curvedPath = L.curve(
                [
                    'M', curvedPathLatLongs.latlng1,
                    'Q', curvedPathLatLongs.midpointLatLng,
                    curvedPathLatLongs.latlng2
                ], pathOptions
            ).addTo(globalMap);

            curvedPath.bindPopup(thisLoc.counter + " movements<br><small>Moving between <b>" + thisLoc.x_city + "</b> and <b>" + thisLoc.y_city + "</b></small>");
        }
    });

}
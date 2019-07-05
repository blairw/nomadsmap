// source: https://medium.com/@ryancatalani/creating-consistently-curved-lines-on-leaflet-b59bc03fa9dc
function calculateCurvedEarthControlPoint(latlng1, latlng2) {
    var offsetX = latlng2[1] - latlng1[1],
	offsetY = latlng2[0] - latlng1[0];

    var r = Math.sqrt( Math.pow(offsetX, 2) + Math.pow(offsetY, 2) ),
        theta = Math.atan2(offsetY, offsetX);

    var thetaOffset = (3.14/10);

    var r2 = (r/2)/(Math.cos(thetaOffset)),
        theta2 = theta + thetaOffset;

    var midpointX = (r2 * Math.cos(theta2)) + latlng1[1],
        midpointY = (r2 * Math.sin(theta2)) + latlng1[0];

    var midpointLatLng = [midpointY, midpointX];
    return {
        "latlng1": latlng1,
        "midpointLatLng": midpointLatLng,
        "latlng2": latlng2,
        "rValue": r
    };
}
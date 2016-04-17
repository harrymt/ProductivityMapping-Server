//
// D3 Word cloud for keywords
//
var fill = d3.scale.category20();

d3.layout.cloud().size([300, 300])
    .words(phpKeywords)
    .rotate(0)
    .font("Arial")
    .fontSize(function(d) { return d.size; })
    .on("end", draw)
    .start();

function draw(words) {
    d3.select(".js-keyword-cloud").append("svg")
        .attr("width", 300)
        .attr("height", 300)
        .append("g")
        .attr("transform", "translate(150,150)")
        .selectAll("text")
        .data(words)
        .enter().append("text")
        .style("font-size", function(d) { return d.size + "px"; })
        .style("font-family", "Arial")
        .style("fill", function(d, i) { return fill(i); })
        .attr("text-anchor", "middle")
        .attr("transform", function(d) {
            return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
        })
        .text(function(d) { return d.text; });
}



//
// Google Maps
//

/**
 * Callback to the Google Maps Javascript file loaded.
 */
function initMap() {

    // Fallback if the php API fails (should not happen)
    var centerPoint = {lat: 37.090, lng: -95.712};
    if(phpZones.length > 0) {
        centerPoint = phpZones[0].center;
    }

    // Create the map.
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 17,
        center: centerPoint,
        scrollwheel: false,
        disableDoubleClickZoom: true
    });

    // Add a marker at the searched location


    // Add all the zones to the map
    for (var zone in phpZones) {
        var cityCircle = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            map: map,
            center: phpZones[zone].center,
            radius: phpZones[zone].radius
        });
    }
}
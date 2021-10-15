function submitArea(locations, lat, long, radius){
    console.log("Submit load");
	$.ajax({
		url: host+"area/submitArea",
		method: "POST",
		dataType: "JSON",
		data: {location: locations, lat: lat, long: long, radius: radius},
		success: function(data){
			if(data.status == 0){
				location.reload();
			}
		},
		error: function(data){

		}
	});
}

$("#area-input").submit(function(e){
	e.preventDefault();
	var location    = $("#location").val();
	var lat         = $("#lat").val();
	var long        = $("#lng").val();
    var radius      = $("#rad").val();
    console.log("submit");
	submitArea(location, lat, long, radius);
});

mapboxgl.accessToken = 'pk.eyJ1Ijoicmh5dWJlbiIsImEiOiJjam9wcGhnOWwwNzVhM2txcTR6Y3FsZmxsIn0.ORq_7cWGSKYhpgXY9T8umQ';
var map = new mapboxgl.Map({
    container: 'map', // container id
    style: 'mapbox://styles/mapbox/streets-v9',
    center: [-96, 37.8], // starting position
    zoom: 3 // starting zoom
});

// Add geolocate control to the map.
map.addControl(new mapboxgl.GeolocateControl({
    positionOptions: {
        enableHighAccuracy: true
    },
    trackUserLocation: true
}));
var marker = new mapboxgl.Marker({
})
    .setLngLat([-96, 37.8])
    .addTo(map);
    
function moveMarker(e){
    marker.setLngLat(e.lngLat);
    console.log(e.lngLat);
    var latlng = e.lngLat;
    $("#lat").val(latlng.lat);
    $("#lng").val(latlng.lng);
}
map.on('click', moveMarker);

var map;
//var icon = new google.maps.MarkerImage('http://static.dajaxproject.com/img/starpointer.png');

/* Create the map */
$(document).ready(function(){
  map = new GMaps({
    div: '#map',
    lat: 38.8900,
    lng: -77.0300,
    zoom: 10
  });
  $.post("getCrimeData.php", {},
		  function(data){
			  for (var i=0; i < data.length; i++) {
			        map.addMarker({
			            lat: data[i].Latitude,
			            lng: data[i].Longitude,
			            color: 'blue',
			            draggable: false,
			        });
			    };
		  }, "json");
});

<html>
<head>
<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<!-- <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script> -->
<!-- <script type="text/javascript" src="js/gmaps.js"></script> -->
<!-- <script type="text/javascript" src="js/index.js"></script> -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAG-kRagHR15KmyImetrFcSXdW3Kll3p8Q&sensor=false"></script>
<script type="text/javascript">
var map;
var root = '/crimewatch/images/markers/'
var icons = ['blue_MarkerA.png', 'blue_MarkerB.png', 'blue_MarkerC.png',
               'brown_MarkerA.png', 'brown_MarkerB.png', 'brown_MarkerC.png',
               'darkgreen_MarkerA.png', 'darkgreen_MarkerB.png', 'darkgreen_MarkerC.png',
               'orange_MarkerA.png', 'orange_MarkerB.png', 'orange_MarkerC.png',
               'purple_MarkerA.png', 'purple_MarkerB.png', 'purple_MarkerC.png',
               'red_MarkerA.png', 'red_MarkerB.png', 'red_MarkerC.png',
               'yellow_MarkerA.png', 'yellow_MarkerB.png', 'yellow_MarkerC.png',
               'green_MarkerA.png', 'green_MarkerB.png']
var coordinates;
var markers = []
$(document).ready(function() {
	//function initialize() {
	var mapOptions = {
		center: new google.maps.LatLng(38.8900, -77.0300),
		zoom: 8,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map"), mapOptions);
	$.post("getCrimeData.php", {},
		function(data) {
			coordinates = data;
			for (var i=0; i < data.length; i++) {
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(data[i].Latitude, data[i].Longitude),
					icon: root + icons[data[i].Cat-1],
					category: data[i].Cat,
					map: map
				});
				markers.push(marker)
			}
		}, "json"
	);
	$.post("getCategories.php", {},
		function(data) {
			var container= $("#categories");
			for (var i=0; i < data.length; i++) {
				var html = '<input type="checkbox" name="' + data[i].cat + '" value="' + data[i].id +
				             '" class="category" checked="checked">' + data[i].cat + '<br>';
				container.append($(html));
			}		
			$('.category').change(function(event) {
				id = event.target.value;
				cat = event.target.name;
				if ($('input[name="'+cat+'"]').is(':checked') == false) {
					//console.log('check is false');
					for (i in markers) {
						//console.log('checkbox category: ' + id + ' marker category: ' + markers[i].category);
						if (markers[i].category == id) {
							markers[i].setMap(null);
						}
					}
				} else {
					for (i in markers) {
						if (markers[i].category == id) {
							markers[i].setMap(map);
						}
					}
				}				
			});	
		}, "json"
	);
})
      
</script>
</head>

<body>
<div id="header">
    <h1>Crime Watch</h1>
</div>

<div id="content" style="border:solid 1px black; width:1000px; margin:auto">
    <div id="main_panel">
        <div id="map" style="height:500px; width:500px">
        </div>
        <div id="filter_panel" style="border:solid 1px blue">
	        <div id="categories">
	        	<h3>Crime Categories</h3>
	        </div>
	        <div id="time_filters">
	        	<h3>Date Range</h3>
				From:<br>
				<select name="from_year">
					<option value="2006"></option>
					<option value="2009">2009</option>
					<option value="2008">2008</option>
					<option value="2007">2007</option>
					<option value="2006">2006</option>
				</select>
				<select name="from_year">
					<option value="1"></option>
					<option value="1">January</option>
					<option value="2">February</option>
					<option value="3">March</option>
					<option value="4">April</option>
					<option value="5">May</option>
					<option value="6">June</option>
					<option value="7">July</option>
					<option value="8">August</option>
					<option value="9">September</option>
					<option value="10">October</option>
					<option value="11">November</option>
					<option value="12">December</option>
				</select><br>
				To:<br>
				<select name="to_year">
					<option value="2006"></option>
					<option value="2009">2009</option>
					<option value="2008">2008</option>
					<option value="2007">2007</option>
					<option value="2006">2006</option>
				</select>
				<select name="to_year">
					<option value="1"></option>
					<option value="1">January</option>
					<option value="2">February</option>
					<option value="3">March</option>
					<option value="4">April</option>
					<option value="5">May</option>
					<option value="6">June</option>
					<option value="7">July</option>
					<option value="8">August</option>
					<option value="9">September</option>
					<option value="10">October</option>
					<option value="11">November</option>
					<option value="12">December</option>
				</select>
			</div>
        </div>
    </div>
    <div id="aux_panel">
        <h1>Lorem ipsum</h1>
        <p>Lorem ipsum</p>
    </div>
</div>

<body>
</html>
<html>
<head>
<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<!-- <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script> -->
<!-- <script type="text/javascript" src="js/gmaps.js"></script> -->
<!-- <script type="text/javascript" src="js/index.js"></script> -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=visualization&key=AIzaSyAG-kRagHR15KmyImetrFcSXdW3Kll3p8Q&sensor=false"></script>
<script type="text/javascript">
var map;
var root = '/crimewatch/images/markers/';
var icons = ['blue_MarkerA.png', 'blue_MarkerB.png', 'blue_MarkerC.png',
               'brown_MarkerA.png', 'brown_MarkerB.png', 'brown_MarkerC.png',
               'darkgreen_MarkerA.png', 'darkgreen_MarkerB.png', 'darkgreen_MarkerC.png',
               'orange_MarkerA.png', 'orange_MarkerB.png', 'orange_MarkerC.png',
               'purple_MarkerA.png', 'purple_MarkerB.png', 'purple_MarkerC.png',
               'red_MarkerA.png', 'red_MarkerB.png', 'red_MarkerC.png',
               'yellow_MarkerA.png', 'yellow_MarkerB.png', 'yellow_MarkerC.png',
               'green_MarkerA.png', 'green_MarkerB.png'];
var coordinates;
var markers = [];
var heatmapData = [];
var pointArray, heatmap;

$(document).ready(function() {
	//function initialize() {
	var mapOptions = {
		center: new google.maps.LatLng(38.8900, -77.2000),
		zoom: 10,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map"), mapOptions);
	$('#from_year').val('2009');
	$('#from_month').val('1');
	$('#to_year').val('2009');
	$('#to_month').val('12');
	getCrimeData();
	$.post("getCategories.php", {},
		function(data) {
			//var container= $("#categories");
			$("#categories").append('<div id="row1" style="width:50%;float:left"></div>');
			$("#categories").append('<div id="row2" style="margin-left:50%;width:50%"></div>');
			var container = $('#row1');
			var half = data.length/2;
			for (var i=0; i < data.length; i++) {
				if (i > half) { container = $('#row2'); }
				var html = '<img src="' + root + icons[data[i].id-1] + '" height="25" width="15" >';
				html += '<input type="checkbox" name="' + data[i].cat + '" value="' + data[i].id +
				             '" class="category" checked="checked">' + data[i].cat + '<br>';				
				container.append($(html));
			}
			container.append('<img src="' + root + icons[data[1].id-1] + '" height="25" width="15" style="visibility:hidden"><input type="checkbox" name="all" id="toggle-category" checked="checked">Select All<br>');
			container.append('<img src="' + root + icons[data[1].id-1] + '" height="25" width="15" style="visibility:hidden"><input type="checkbox" name="all" id="toggle-heatmap" checked="checked">Show Heatmap<br>');
			$('select').change(function() {
				var from_year = $('#from_year').val()
				var from_month = $('#from_month').val()
				var to_year = $('#to_year').val()
				var to_month = $('#to_month').val()
				if ((from_year > to_year) || ((from_year == to_year) && (from_month > to_month))) alert("From Date has to be before To Date")
				else getCrimeData()
				
			});	
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
			$('#toggle-category').change(function(event) {
				if (heatmap != null) return;
				id = event.target.value;
				cat = event.target.name;
				if ($('input[name="'+cat+'"]').is(':checked') == false) {
					$(".category").each( function() {
						$(this).attr("checked",false);
						$(this).trigger('change');
					});
				} else {
					$(".category").each( function() {
						$(this).attr("checked",true);
						$(this).trigger('change');
					});
				}				
			});
			$('#toggle-heatmap').change(function(event) {
				toggleHeatmap();				
			});
		}, "json"
	);
})

function getCrimeData() {
	deleteMarkers()
	$("input[type='checkbox']").attr('checked', 'true');
	$.post("getCrimeData.php", 
			{
				'from_year':  $('#from_year').val(),
				'from_month': $('#from_month').val(),
				'to_year':    $('#to_year').val(),
				'to_month':   $('#to_month').val(),
			},
		function(data) {
			coordinates = data;
			for (var i=0; i < data.length; i++) {
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(data[i].Latitude, data[i].Longitude),
					icon: root + icons[data[i].Cat-1],
					category: data[i].Cat,
					map: map
				});
				markers.push(marker);
				heatmapData.push(new google.maps.LatLng(data[i].Latitude, data[i].Longitude));				
			}
		}, "json"
	);	
}
// Deletes all markers in the array by removing references to them
function deleteMarkers() {
  if (markers) {
    for (i in markers) {
      markers[i].setMap(null);
    }
    markers.length = 0;
  }
  if (heatmapData) { heatmapData.length = 0; }
}

function toggleHeatmap() {	
	if (heatmap == null) {
		$('#toggle-category').attr('checked',false);
		$('#toggle-category').trigger('change');
		pointArray = new google.maps.MVCArray(heatmapData);
		heatmap = new google.maps.visualization.HeatmapLayer({
        	data: pointArray,
        	radius: 30
    	});
		heatmap.setMap(map);
	} else {
		heatmap.setMap(null);
		heatmap = null;
		pointArray = null;
		$('#toggle-category').attr('checked',true);
		$('#toggle-category').trigger('change');
	}
}
</script>
</head>

<body>
<div id="header" style="width:1000px;margin:auto;text-align:center">
    <h1>Crime Watch</h1>
</div>

<div id="content" style="border:solid 1px black; width:1000px; margin:auto; overflow: hidden">
    <div id="main_panel" style="overflow: hidden">
        <div id="map" style="height:550px; width:500px; float: left">
        </div>
        <div id="filter_panel" style="margin-left:500px; border:solid 1px blue; height:550px; width:500px">
	        <div id="categories" style="border:solid 1px black; overflow: hidden; padding:0 10px;">
	        	<h3>Crime Categories</h3>
	        </div>
	        <div id="time_filters" style="padding:0 10px">
	        	<h3>Date</h3>
				<span style="width:45%; float:left; text-align: right">
				From:
				<select id="from_year">
					<option value="2009">2009</option>
					<option value="2008">2008</option>
					<option value="2007">2007</option>
					<option value="2006">2006</option>
					<option value="2005">2005</option>
					<option value="2004">2004</option>
					<option value="2003">2003</option>					
				</select>
				<select id="from_month">
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
				</span>
				<span style="width:45%; float:right; text-align: left">
				To:
				<select id="to_year">
					<option value="2009">2009</option>
					<option value="2008">2008</option>
					<option value="2007">2007</option>
					<option value="2006">2006</option>
					<option value="2005">2005</option>
					<option value="2004">2004</option>
					<option value="2003">2003</option>	
				</select>
				<select id="to_month">
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
				</span>
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
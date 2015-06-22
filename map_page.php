<!DOCTYPE html>
<?php 
//session_start();

mysql_connect('localhost', 'thang', 'tatthang') 
    or die("cannot connect to database\n");
include('db_handler.php');


mysql_select_db("my_db") or die(mysql_error());
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content=" initial-scale=1.0, user-scalable=no"/>
        <meta http-equiv="Pragma" content="no-cache">
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"> </script>
        <script type="text/javascript" src="script/Map_handler.js"></script>
        <script type="text/javascript" src="script/tooltip.js"></script>
        <script type="text/javascript" src="script/jquery/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="script/jquery/jquery-ui-1.8.custom.min.js"></script>
		<script type="text/javascript" src="script/markerclusterer.js"></script>
		<script type="text/javascript" src="script/ArrayCop_forIW.js"></script>
        <link rel="stylesheet" type="text/css" href="StyleSheet.css" media="screen" />
        <style type="text/css">
			#memoField{
				position: fix;
				margin: auto
				float: left;
				padding: 10px;
				text-align: left;
				font: normal 80%;
			}
			#map_canvas{
				padding: 10px;
				margin-top: 7px;
			}
			#popup_button_onIW{
				margin: auto;
				padding: 10px;
				position: relative;
			}  
			button.back_button_onIW{
				margin-left: auto;
				margin-right: auto;
				display: block;
				width: 200px;
				height: 30px;
				float: right;
			}
        </style>
        <script type="text/javascript">						
			var auto_refresh = setInterval(
				function(){
					$('#google-map').load('getdata_forIW.php').fadeIn("fast");
				},10000);
			$(document).ready(function(){
						var mapH = $(window).height() - 150;
						var mapW = $(window).width() - 40;
						$("#map_canvas").css('height', mapH);
						$("#map_canvas").css('width', mapW);
						$("#memoField").css('width', mapW);
					});
					$(window).resize(function(){
						var mapH = $(window).height() - 150;
						var mapW = $(window).width() - 40;
						$("#map_canvas").css('height', mapH);
						$("#map_canvas").css('width', mapW);
						$("#memoField").css('width', mapW);
			});
		</script>	
    </head>
    <body>
			<form name="newform">
				<textarea name ="newarea" id="memoField" rows="2" title="Arbitrary memo here!" onclick="document.newform.newarea.value='';">
					Arbitrary memo here!
				</textarea>
			</form>
			
			<div id="google-map">
			</div>
			<div id="map_canvas">
				<script type="text/javascript">
					initialize();
					var e = window.opener.document.getElementById('popup_button');
					var dl = e.getElementsByTagName('select')[0];
					//alert("index "+dl.options[dl.selectedIndex].value);
					if(dl.options[dl.selectedIndex].value != "none"){
						var index = parseInt(dl.options[dl.selectedIndex].value);
						var lat = window.opener.window.AreasDef[index].center_lat;
						var lng = window.opener.window.AreasDef[index].center_long;
						window.map.panTo(new  google.maps.LatLng(lat, lng));
					}
					$('#map_canvas').load('firstgetdata_forIW.php');
				</script>				
			</div>
			<div id="popup_button_onIW">
				<script type="text/javascript">var buttonClick = 0;</script>
				<button type="button" class="back_button_onIW" onClick="javascript: comeBackMap()"> Reset Map</button>
			</div>
	</body>
</html>


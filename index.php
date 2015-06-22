<!DOCTYPE html>
<?php
error_reporting(E_ALL ^ E_NOTICE); 
//session_start();

include('db_handler.php');
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	    <meta name="viewport" content=" initial-scale=1.0, user-scalable=no"/>
        <meta http-equiv="Pragma" content="no-cache">
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"> </script>
        <script src="script/css_browser_selector.js" type="text/javascript"></script>
        <script type="text/javascript" src="script/Map_handler.js"></script>
        <script type="text/javascript" src="script/areaDefHandler.js"></script>
        <script type="text/javascript" src="script/tooltip.js"></script>
        <script type="text/javascript" src="script/jquery/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="script/jquery/jquery-ui-1.8.custom.min.js"></script>
		<script type="text/javascript" src="script/markerclusterer.js"></script>
		<script type="text/javascript" src="script/camera_handler.js"></script>
		<script type="text/javascript" src="script/sorttable.js"></script>
		<script type="text/javascript" src="script/table_handler.js"></script>
		<script type="text/javascript" src="graph/jquery.jqplot.min.js"></script>
		<script type="text/javascript" src="graph/plugins/jqplot.logAxisRenderer.min.js"></script>
		<script type="text/javascript" src="graph/plugins/jqplot.canvasTextRenderer.min.js"></script>
		<script type="text/javascript" src="graph/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
		<script type="text/javascript" src="graph/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
		<script type="text/javascript" src="graph/plugins/jqplot.dateAxisRenderer.min.js"></script>
		<script type="text/javascript" src="graph/plugins/jqplot.categoryAxisRenderer.min.js"></script>
		<script type="text/javascript" src="graph/plugins/jqplot.barRenderer.min.js"></script>
		<script type="text/javascript" src="script/graph_handler.js"></script>
		<script type="text/javascript" src="script/dateformat.js"></script>
		<link rel="stylesheet" type="text/css" href="graph/jquery.jqplot.min.css" />
        <link rel="stylesheet" type="text/css" href="table/style.css" />
	    <link rel="stylesheet" type="text/css" href="devheart-examples.css" media="screen" />
	    <link href="main.css" rel="stylesheet" type="text/css" />
		<!--[if IE 6]>
		<link href="css/ie6.css" rel="stylesheet" type="text/css" />
		<![endif]-->
        <title>Disaster Rescue Support Center</title>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#graph').load('dataForGraph.php');
			});	
				
			var auto_refresh = setInterval(
				function (){ 							
					$('#google_map').load('getdata.php').fadeIn("fast");
				},10000);
										
			var auto_refresh1 = setInterval(
				function(){
					$('#graph').load('continuousGraph.php').fadeIn("fast");
				},10000);
			
		</script>
</head>
<body>
<div id="header-wrap">
	<div id="header-container">
		<div id="header">
			<h1 class="page-title">Disaster Rescue Support Center</h1>
			<ul>
				<li><a href="javascript: showGraph();">Statistic</a></li>
			</ul>
		</div>
	</div>
</div>
<div id="ie6-container-wrap">
	<div id="container">
		<div id="google_map"></div>
        <div id="map_canvas">
			<!-- This is container of google map-->
			<script type="text/javascript">
				initialize();
				//testCamera();
				$('#map_canvas').load('firstgetdata.php');
			</script>				
		</div>	
		
		
				<div id="map-button">
					<button type="button" class="back_button" onClick="javascript: comeBackMap()" style="right: 20px"> Reset Map</button>
				</div>
				<div id="Two-tables">
				<div id="RT">
					<p>Recommending Queue</p>
					<table class="sortable" id="rounded-corner">
						<thead>
							<tr>
								<th class="c1">Urgency</th>
								<th class="c2"><b>Name</b></th>
								<th class="c3"><b>Wait</b></th>
								<th class="c4"><b>Area</b></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="4" class="rounded-foot-left" style="font-size: 21px"><em>This is statistic of all victim on this table</em></td>
							</tr>
						</tfoot>
						<tbody onclick="SelectRec(event)">
						</tbody>
					</table>
				</div>
				<div id="PT">
					<p>Processing Queue</p>
					<table class="sortable" id="rounded-corner">
						<thead>
							<tr>
								<th class="c1">Urgency</th>
								<th class="c2"><b>Name</b></th>
								<th class="c3"><b>Wait</b></th>
								<th class="c4"><b>Area</b></th>
							</tr>
						</thead>	
						<tfoot>
							<tr>
								<td colspan="4" class="rounded-foot-left" style="font-size: 21px"><em>This is statistic of all victim on this table</em></td>
							</tr>
						</tfoot>
						<tbody class="table-body"  onclick="SelectPro(event)">
						</tbody>	
					</table>
				</div>
				<div id="UDB">
					<button type="button" id="downbutton" onclick="javascript: RecToPro()"><img src="images/right_button.png" alt="Image not found" width="27px" height="44px" style="padding-top: 6px"></img></button>
					<button type="button" id="upbutton" onclick="javascript: ProToRec()"><img src="images/left_button.png" alt="Image not found" width="27px" height="44px" style="padding-top: 6px"></img></button>
				</div>
				<div class="cleaner">
					&nbsp;
				</div>						
			</div> <!--end of Two-Table-->	
	
	</div>
</div> <!-- End of contain area-->

		<div id="details-box-wrap">
			<div id="details-box-container">
				<div id="details-box">
					<table class="victim-details">
						<tr>
							<th>Click on a pin to show victim details</th>
						</tr>
					</table>					
				</div>
			</div>	
		</div>

        <div id="explain-box-wrap">
			<div id="explain-box-container">
				<div id="explain-box">
					<table class="explain-details">
						<tr>
							<th>
                                <ul>
                                    <li>This is a web page to support the disaster rescuing.</li>
                                    <li>All the registed user's position and health condition is displayed in the maps.</li>
                                    <li>The web site provides all needed information to help the Rescue Center to execute, prioritize and plan the rescue process.</li>
                                    <li>For more information, please view this <a href="/Presentation">presentation.</a></li>
                                </ul>
                            </th>
						</tr>
					</table>					
				</div>
			</div>	
		</div>
		
		<div id="graph-wrap">
			<div id="graph-container">
				<div id="graph">
				</div>
			</div>
		</div>
		
<div id="footer-wrap">
	<div id="footer-container">
		<div id="footer">
			<ul class="left-list">
				<li><a href="javascript: showMapEplaination();">Map Explaination</a></li>
			</ul>
			<div id="popup_button">
				<select class="drop-list">
					<option value="none">None</option>
				</select>
				<script type="text/javascript">var buttonClick = 0;</script>
				<button type="button" class="map_button" onclick="javascript:Open_Map_Window()"> Open Map in new window</button>			
			</div>		
			<ul>
				<li><a href="javascript: showDetailsBox();">Details</a></li>
			</ul>
		</div>
	</div>
</div>


<script type="text/javascript">
	var showDB = 0;
	var showG = 0;
    var showD = 0;
    
	function showDetailsBox(){
		if(window.showDB==0){
			$('#details-box').stop().animate({'bottom' : '307px','right' : '-34px'}, 500);
			window.showDB=1;
		}else{
			$('#details-box').stop().animate({'bottom' : '20px','right' : '-210px'}, 500);
			window.showDB=0;
		}
	}
	function showGraph(){
		if(window.showG==0){
			$('#graph').stop().animate({'top' : '1px','right' : '-20px'}, 500);
			window.showG=1;
		}else{
			$('#graph').stop().animate({'top' : '-220px','right' : '-245px'}, 500);
			window.showG=0;
		}
	}
    
    function showMapEplaination(){
		if(window.showD==0){
			$('#explain-box').stop().animate({'bottom' : '307px','left' : '-34px'}, 500);
			window.showD=1;
		}else{
			$('#explain-box').stop().animate({'bottom' : '20px','left' : '-210px'}, 500);
            window.showD=0;
		}
	}

	/*
	$(document).ready(function(){
		var data = [[-5,2],[-10,3],[-15,1],[-20,5],[-25,2],[-30,4],[-35,1],[-40,2]];
		var plot2 = $.jqplot ('graph', [data], {
		title: 'Plot With Options',
		axes: {
         xaxis: {
          label: 'Time (minutes)',       
        },
        yaxis: {
          label: 'Message',
          labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
        }
      }
	  });
	});  for test only */
	
	function Open_Map_Window(){
			var e = document.getElementById('popup_button');
			var dl = e.getElementsByTagName('select')[0];
			var dlindex = parseInt(dl.value.id);
			var winTile = "Indication map window";
			if(window.buttonClick==0){
				window.open("map_page.php",winTile,"menubar=1,resizable=1,width=730,height=745");
			}else{
				var Tile = winTile + window.buttonClick;
				window.open("map_page.php",Tile,"menubar=1,resizable=1,width=730,height=745");
			}
			window.buttonClick++;
		}
	
</script>
</body>
</html>

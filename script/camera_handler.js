var camera_pin = 'images/pin/camera.png';
var cameraip=null;
var Camera_Array=[];



function camera(map, location, lat, lng,ip){
	this.marker =  new google.maps.Marker({
		map: map,
        icon: camera_pin,
        draggable: false,
        position: new google.maps.LatLng(lat, lng),
	});
	
	this.location=location;
	this.lat=lat;
	this.lng=lng;
	this.ip=ip;
	this.infoWin = new google.maps.InfoWindow({});
	
	var detail = document.createElement('div');
	detail.innerHTML = '<p id=\"IWC\">'+location+'</p>'+
						'<p id=\"IWC\">IP: '+ip+'</p>'+
						'<button type=\"button\" onclick=\"javascript: showcamera(\''+ip+'\')\"> Show </button>';
	this.infoWin.setContent(detail);
	addEventsCam(this,window.map);
}

function addEventsCam(camera, map){
					google.maps.event.addListener(camera.marker, "click", function(){
						camera.infoWin.open(map, camera.marker);							
					});
				} 


function showcamera(ip){
	cameraip = ip;
	var consoleRef=window.open('camera.html','Camera window',"menubar=1,resizable=1,width=690,height=500");
}

function testCamera(){
	var cam = new camera(window.map, 'CCL305E','37.508292', '139.930158', '192.168.99.199');
	cam.marker.setMap(window.map);
	window.Camera_Array.push(cam);
}
